<?php

namespace App\Services;

use App\Http\Resources\LeaderboardResource;
use App\Models\UserAnswer;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Cache;

class LeaderboardService
{
  public function getLeaderboardData()
  {
    $leaderboardData = Cache::remember('leaderboard', now()->addMinutes(10), function () {
      return UserProfile::select('id', 'user_id', 'firstname', 'lastname', 'xp_points')
        ->orderBy('xp_points', 'desc')
        ->take(10)
        ->get();
    });

    $rankedLeaderboard = $leaderboardData->map(function ($profile, $key) {
      $profile->rank = $key + 1;
      return $profile;
    });

    return LeaderboardResource::collection($rankedLeaderboard);
  }

  public function getWeeklyLeaderboardData()
  {
    $cacheKey = 'leaderboard_weekly_' . now()->year . '_' . now()->weekOfYear;

    $leaderboardData = Cache::remember($cacheKey, now()->addMinutes(30), function () {
      $activeProfileIds = UserAnswer::where('created_at', '>=', now()->startOfWeek())
        ->distinct('user_profile_id')
        ->pluck('user_profile_id');

      return UserProfile::whereIn('id', $activeProfileIds)
        ->select('id', 'user_id', 'xp_points', 'firstname', 'lastname') // <-- UBAH DI SINI
        ->orderBy('xp_points', 'desc')
        ->take(10)
        ->get();
    });

    // Bagian ini tetap sama
    $rankedLeaderboard = $leaderboardData->map(function ($profile, $key) {
      $profile->rank = $key + 1;
      return $profile;
    });

    return LeaderboardResource::collection($rankedLeaderboard);
  }
}
