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
        $profile = $user->profile;

        // Jika profil belum ada, kembalikan data default yang bersih.
        if (!$profile) {
            return $this->successResponse(
                UserProfileResource::defaultProfileData($user),
                'Profil belum dilengkapi, menampilkan data default.'
            );
        }

        // Eager load relasi untuk mencegah N+1 query di dalam resource.
        $profile->load('badges');

        // Cukup kembalikan resource. Semua logika format ada di dalamnya.
        return $this->successResponse(
            new UserProfileResource($profile),
            'Profil berhasil diambil.'
        );
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
