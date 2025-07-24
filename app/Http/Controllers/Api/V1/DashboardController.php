<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResource;
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
            // Jika pengguna belum melengkapi profilnya, kembalikan struktur data default.
            return $this->successResponse([
                'level' => ['level' => 1, 'progress_percent' => 0],
                'streak' => 0,
                'stats' => ['total_correct_answers' => 0, 'units_completed' => 0],
                'next_unit' => Unit::orderBy('order')->first() ? new UnitResource(Unit::orderBy('order')->first()) : null,
                'recent_badges' => [],
                'weekly_leaderboard' => [],
                'activity_chart' => [],
            ], 'Dashboard data default.');
        }

        // Eager load relasi untuk menghindari N+1 query problem di dalam resource.
        $profile->load('answers', 'progress', 'badges');

        // Cukup kembalikan resource baru. Semua logika format ada di dalam DashboardResource.
        return $this->successResponse(
            new DashboardResource($profile),
            'Dashboard data berhasil diambil.'
        );
    }
}
