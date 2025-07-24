<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    use ApiResponse;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): AuthResource
    {
        $data = $this->authService->register($request->validated());
        return new AuthResource($data);
    }

    public function login(LoginRequest $request): AuthResource
    {
        $data = $this->authService->login($request->validated());
        return new AuthResource($data);
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return response()->json(['message' => 'Berhasil logout.'])->setStatusCode(200);
    }

    public function me(Request $request): JsonResponse
    {
        return $this->successResponse(new UserResource($request->user()), 'Data pengguna berhasil diambil.');
    }
}
