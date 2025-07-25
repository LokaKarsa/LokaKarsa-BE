<?php

namespace App\Http\Resources;

use App\Http\Traits\ManagesActivityData;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class UserProfileResource extends JsonResource
{
    use ManagesActivityData;
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

        // --- Kalkulasi Statistik Tambahan ---
        $totalAnswers = $profile->answers->count();
        $correctAnswers = $profile->answers->where('is_correct', true)->count();
        $accuracy = ($totalAnswers > 0) ? round(($correctAnswers / $totalAnswers) * 100) : 100;
        $minutesSpent = round(($totalAnswers * self::AVG_SECONDS_PER_QUESTION) / 60);

        // --- Logika Galeri Lencana ---
        $allBadges = Badge::all();
        $unlockedBadges = $profile->badges->keyBy('id'); // Jadikan ID sebagai key untuk pencarian cepat

        $badgeGallery = $allBadges->map(function ($badge) use ($unlockedBadges) {
            $isUnlocked = $unlockedBadges->has($badge->id);
            $unlockedBadge = $isUnlocked ? $unlockedBadges->get($badge->id) : null;

            return [
                'name' => $badge->name,
                'description' => $badge->description,
                'icon_url' => $badge->icon_url,
                'is_unlocked' => $isUnlocked,
                'unlocked_at' => $isUnlocked ? $unlockedBadge->pivot->unlocked_at : null,
            ];
        });

        // --- Rakit Respons Akhir ---
        return [
            'user_info' => [
                'firstname' => $profile->firstname,
                'lastname' => $profile->lastname,
                'bio' => $profile->bio ?? 'Pelajar Aksara Jawa',
                'title' => $profile->title,
                'avatar_url' => $profile->avatar_url,
                'sex' => $profile->sex,
                'region' => $profile->region,
                'date_of_birth' => $profile->date_of_birth ? $profile->date_of_birth->format('Y-m-d') : null,
            ],
            'stats' => [
                'total_xp' => $profile->xp_points,
                'characters_mastered' => $profile->characters_mastered,
                'highest_streak' => $profile->highest_streak,
                'accuracy_percent' => $accuracy,
                'total_minutes_spent' => $minutesSpent,
            ],
            'last_activity_summary' => Cache::get('profile:' . $profile->id . ':last_activity_summary'),
            'activity_chart' => $this->getActivityData($profile->id),
            'badge_gallery' => $badgeGallery,
        ];
    }

    /**
     * Generate a default data structure for users without a profile.
     *
     * @param  \App\Models\User $user
     * @return array
     */
    public static function defaultProfileData(User $user): array
    {
        // Ambil semua lencana dan format sebagai 'locked'
        $badgeGallery = Badge::all()->map(fn($badge) => [
            'name' => $badge->name,
            'description' => $badge->description,
            'icon_url' => $badge->icon_url,
            'is_unlocked' => false,
            'unlocked_at' => null,
        ]);

        return [
            'user_info' => [
                'fullname' => $user->name,
                'bio' => 'Pelajar Aksara Jawa',
                'title' => 'Cantrik', // Gelar awal
                'avatar_url' => null,
            ],
            'stats' => [
                'total_xp' => 0,
                'characters_mastered' => 0,
                'highest_streak' => 0,
                'accuracy_percent' => 100,
                'total_minutes_spent' => 0,
            ],
            'last_activity_summary' => null,
            'activity_chart' => [],
            'badge_gallery' => $badgeGallery,
        ];
    }
}
