<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Traits\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;


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
        // Validate credentials
        $credentials = $request->validated();

        // Attempt login using JWT
        if ($token = JWTAuth::attempt($credentials)) {
            return new AuthResource(['token' => $token]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout(): JsonResponse
    {
        // Invalidate the current JWT token
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out.'])->setStatusCode(200);
    }
}
