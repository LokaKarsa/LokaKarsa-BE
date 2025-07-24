<?php

namespace App\Http\Resources;

use App\Http\Traits\ManagesActivityData;
use App\Models\Badge;
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
        $user = $request->user();

        // --- Logika untuk menggabungkan semua lencana ---
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

        // --- Ambil data lain ---
        $lastActivitySummary = Cache::get('profile:' . $profile->id . ':last_activity_summary');

        // --- Rakit respons akhir ---
        return [
            'user_info' => [
                'firstname' => $profile->firstname,
                'lastname' => $profile->lastname,
                'bio' => $profile->bio,
                'sex' => $profile->sex,
                'region' => $profile->region,
                'date_of_birth' => $profile->date_of_birth,
            ],
            'stats' => [
                'total_xp' => $profile->xp_points,
                'characters_mastered' => $profile->characters_mastered,
                'highest_streak' => $profile->highest_streak,
            ],
            'last_activity_summary' => $lastActivitySummary,
            'activity_chart' => $this->getActivityData($profile->id),
            'badge_gallery' => $badgeGallery,
        ];
    }

    public static function defaultProfileData($user): array
    {
        return [
            'user_info' => [
                'fullname' => $user->name,
                'bio' => 'Pelajar Aksara Jawa',
                'title' => 'Cantrik',
                'avatar_url' => null,
            ],
            'stats' => [
                'total_xp' => 0,
                'characters_mastered' => 0,
                'highest_streak' => 0,
            ],
            'last_activity_summary' => null,
            'activity_chart' => [],
            'badge_gallery' => Badge::all()->map(fn($badge) => [
                'name' => $badge->name,
                'description' => $badge->description,
                'icon_url' => $badge->icon_url,
                'is_unlocked' => false,
                'unlocked_at' => null,
            ]),
        ];
    }
}
