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
        $profile = $user->profile()->with('badges')->first();

        // Best Practice: Gunakan null-safe operator (?->) dan null coalescing (??)
        // untuk menangani kasus di mana $profile tidak ada (null).

        $profileId = $profile?->id;

        $lastActivitySummary = $profileId ? Cache::get('profile:' . $profileId . ':last_activity_summary') : null;

        // Merakit data untuk respons, baik profil ada maupun tidak.
        $profileData = [
            'user_info' => [
                // Jika profil ada, gabungkan nama depan & belakang. Jika tidak, pakai nama dari tabel user.
                'fullname' => $profile ? ($profile->firstname . ' ' . $profile->lastname) : $user->name,
                'bio' => $profile?->bio ?? 'Pelajar Aksara Jawa',
                'email' => $user->email
            ],
            'stats' => [
                'total_xp' => $profile?->xp_points ?? 0,
                'characters_mastered' => $profile?->characters_mastered ?? 0,
                'highest_streak' => $profile?->highest_streak ?? 0,
            ],
            // Jika ada profileId, ambil data aktivitas. Jika tidak, kembalikan array kosong.
            'activity_chart' => $profileId ? $this->getActivityData($profileId) : [],
            'last_activity_summary' => $lastActivitySummary,
            'badge_gallery' => BadgeResource::collection($profile->badges),
            // Jika profil ada, ambil badges. Jika tidak, kembalikan array kosong.
        ];

        $message = $profile ? 'Profil berhasil diambil.' : 'Profil belum dilengkapi, menampilkan data default.';

        return $this->successResponse($profileData, $message);
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
