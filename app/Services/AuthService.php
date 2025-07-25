<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
  public function register(array $data): array
  {
    // Membuat user baru
    $user = User::create([
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
    ]);

    // Menghasilkan JWT token setelah registrasi
    $token = JWTAuth::fromUser($user);

    return ['user' => $user, 'token' => $token];
  }

  public function login(array $data): array
  {
    // Menemukan user berdasarkan email
    $user = User::where('email', $data['email'])->first();

    // Memeriksa kredensial user
    if (! $user || ! Hash::check($data['password'], $user->password)) {
      throw ValidationException::withMessages([
        'email' => ['Kredensial yang diberikan salah.'],
      ]);
    }

    // Menghasilkan JWT token setelah login
    $token = JWTAuth::fromUser($user);

    return ['user' => $user, 'token' => $token];
  }

  public function logout(): void
  {
    // Menonaktifkan token JWT (invalidate)
    JWTAuth::invalidate(JWTAuth::getToken());
  }
}
