<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\UserProfile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class BadgeService
{
    /**
     * Checks for and awards badges based on a specific trigger type.
     * This is the main entry point for the service.
     *
     * @param UserProfile $profile The user profile to check.
     * @param string $triggerType The type of event that occurred (e.g., 'XP', 'STREAK').
     * @return Collection A collection of Badge models that were newly awarded.
     */
    public function checkAndAwardBadges(UserProfile $profile, string $triggerType): Collection
    {
        // Determine the user's current progress value based on the trigger type.
        $currentValue = match($triggerType) {
            'XP' => $profile->xp_points,
            'STREAK' => $profile->streak_days,
            default => 0,
        };

        // Log the check for debugging purposes.
        Log::info("Checking for '{$triggerType}' badges for profile {$profile->id}. Current Value: {$currentValue}");

        // Get the IDs of badges the user already owns to avoid re-awarding them.
        $ownedBadgeIds = $profile->badges()->pluck('id');

        // Find all potential new badges:
        // 1. Must match the trigger type.
        // 2. Must NOT be in the list of badges the user already owns.
        // 3. The user's current value must meet or exceed the badge's condition.
        $newlyAwardedBadges = Badge::where('type', $triggerType)
            ->whereNotIn('id', $ownedBadgeIds)
            ->where('condition_value', '<=', $currentValue)
            ->get();

        // If any new badges were found, attach them to the user's profile.
        if ($newlyAwardedBadges->isNotEmpty()) {
            Log::info("Found {$newlyAwardedBadges->count()} new '{$triggerType}' badges to award profile {$profile->id}.");
            
            // The attach method efficiently links all new badges in the pivot table.
            $profile->badges()->attach($newlyAwardedBadges->pluck('id'));
        }

        // Return the collection of newly awarded badges.
        return $newlyAwardedBadges;
    }
}
