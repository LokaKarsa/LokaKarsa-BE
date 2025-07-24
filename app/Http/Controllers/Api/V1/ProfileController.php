<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Profile\StoreUpdateRequest;
use App\Http\Resources\UserProfileResource;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use ApiResponse;

    // GET Profile
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return $this->errorResponse('Profil pengguna tidak ditemukan.', 404);
        }

        return $this->successResponse(new UserProfileResource($profile), 'Profil berhasil diambil.');
    }

    // POST atau PATCH Profile
    public function storeOrUpdate(StoreUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();

        // Logika elegan updateOrCreate
        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id], // Kondisi pencarian
            $validatedData              // Data untuk di-update atau di-create
        );

        return $this->successResponse(new UserProfileResource($profile), 'Profil berhasil disimpan.');
    }
}
