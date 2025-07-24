<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Profile\StoreUpdateRequest;
use App\Http\Resources\BadgeResource;
use App\Http\Resources\UserProfileResource;
use App\Http\Traits\ApiResponse;
use App\Http\Traits\ManagesActivityData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    use ApiResponse;
    use ManagesActivityData;

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->profile; // Eager load badges

        if (!$profile) {
            return $this->errorResponse('Profil pengguna tidak ditemukan.', 404);
        }

        $lastActivitySummary = Cache::get('profile:' . $profile->id . ':last_activity_summary');


        // Merakit data untuk respons
        $profileData = [
            'user_info' => [
                'fullname' => $profile->firstname . ' ' . $profile->lastname,
                'bio' => $profile->bio ?? 'Pelajar Aksara Jawa',
            ],
            'stats' => [
                'total_xp' => $profile->xp_points,
                'characters_mastered' => $profile->characters_mastered, // Attribute baru
                'highest_streak' => $profile->highest_streak,
            ],
            'activity_chart' => $this->getActivityData($profile->id),
            'last_activity_summary' => $lastActivitySummary,
            // 'badge_gallery' => BadgeResource::collection($profile->badges),
        ];

        return $this->successResponse($profileData, 'Profil berhasil diambil.');
    }

    // POST atau PATCH Profile
    public function storeOrUpdate(StoreUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();

        // Logika elegan updateOrCreate
        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $validatedData
        );

        return $this->successResponse(new UserProfileResource($profile), 'Profil berhasil disimpan.');
    }

    // public function showBadges(Request $request): JsonResponse
    // {
    //     $profile = $request->user()->profile;

    //     if (!$profile) {
    //         return $this->errorResponse('Profil pengguna tidak ditemukan.', 404);
    //     }

    //     // Eager load relasi 'badges'
    //     $badges = $profile->badges()->get();

    //     return $this->successResponse(BadgeResource::collection($badges), 'Lencana berhasil diambil.');
    // }
}
