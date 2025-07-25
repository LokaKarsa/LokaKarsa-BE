<?php

namespace App\Http\Resources;

use App\Http\Traits\ManagesActivityData;
use App\Models\Unit;
use App\Services\LeaderboardService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
  use ManagesActivityData; // Menggunakan trait untuk konsistensi

  // Konstanta ini dibutuhkan oleh trait ManagesActivityData
  const AVG_SECONDS_PER_QUESTION = 45;

  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    // Di sini, $this->resource adalah instance dari model UserProfile
    $profile = $this->resource;

    // --- Semua logika perakitan data sekarang ada di sini ---

    // Hitung statistik keseluruhan
    $stats = [
      'total_correct_answers' => $profile->answers->where('is_correct', true)->count(),
      'units_completed' => $profile->progress->where('status', 'completed')->count(),
    ];

    // Cari unit latihan berikutnya
    $nextUnitModel = Unit::whereDoesntHave('userProgress', function ($query) use ($profile) {
      $query->where('user_profile_id', $profile->id)->where('status', 'completed');
    })->orderBy('order')->first();

    // Ambil 5 lencana terbaru
    $recentBadges = $profile->badges()->latest('pivot_unlocked_at')->take(5)->get();

    // Panggil service lain dari service container
    $leaderboardService = app(LeaderboardService::class);

    // Kembalikan struktur JSON yang lengkap
    return [
      'level' => $this->whenNotNull($profile->level),
      'streak' => $this->whenNotNull($profile->streak_days),
      'stats' => $stats,
      'next_unit' => $nextUnitModel ? new UnitResource($nextUnitModel) : null,
      'recent_badges' => BadgeResource::collection($recentBadges),
      'weekly_leaderboard' => $leaderboardService->getLeaderboardData(),
      'activity_chart' => $this->getActivityData($profile->id),
    ];
  }
}
