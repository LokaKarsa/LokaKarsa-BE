<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UnitResource;
use App\Http\Traits\ApiResponse;
use App\Http\Traits\ManagesActivityData;
use App\Models\Unit;
use App\Services\LeaderboardService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ApiResponse;
    use ManagesActivityData;

    protected $leaderboardService;
    const AVG_SECONDS_PER_QUESTION = 45;

    public function __construct(LeaderboardService $leaderboardService)
    {
        $this->leaderboardService = $leaderboardService;
    }

    public function index(Request $request): JsonResponse
    {
        $profile = $request->user()->profile;

        if (!$profile) {
            return $this->errorResponse('Profil pengguna tidak ditemukan.', 404);
        }

        // 1. Ambil data Streak
        $streak = $profile->streak_days;

        // 2. Ambil data Leaderboard dari service yang sudah kita buat
        $leaderboard = $this->leaderboardService->getWeeklyLeaderboardData();

        // 3. Ambil data Activity (GitHub Style)
        $activity = $this->getActivityData($profile->id);

        // 4. [BARU] Ambil informasi level dari attribute
        $levelInfo = $profile->level;

        // 5. Ambil Lencana Terbaru
        // $recentBadges = $profile->badges()->latest('unlocked_at')->take(3)->get();

        // 3. Ambil Rekomendasi Latihan Berikutnya
        $nextUnitModel = Unit::whereDoesntHave('userProgress', function ($query) use ($profile) {
            $query->where('user_profile_id', $profile->id)->where('status', 'completed');
        })->orderBy('order')->first();
        // Gunakan API Resource agar formatnya konsisten
        $nextUnit = $nextUnitModel ? new UnitResource($nextUnitModel) : null;

        // 4. Ambil Statistik Keseluruhan
        $stats = [
            'total_correct_answers' => $profile->answers()->where('is_correct', true)->count(),
            'units_completed' => $profile->progress()->where('status', 'completed')->count(),
        ];

        // 5. Gabungkan semua data
        $dashboardData = [
            'level' => $levelInfo,
            'streak' => $streak,
            'leaderboard' => $leaderboard,
            'activity' => $activity,
            'stats' => $stats,
            'next_unit' => $nextUnit,
            // 'recent_badges' => BadgeResource::collection($recentBadges),
            'weekly_leaderboard' => $leaderboard,
        ];

        return $this->successResponse($dashboardData, 'Dashboard data berhasil diambil.');
    }
}
