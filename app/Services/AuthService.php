<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
  public function register(array $data): array
  {
    $user = User::create([
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return ['user' => $user, 'token' => $token];
  }

  public function login(array $data): array
  {
    $user = User::where('email', $data['email'])->first();

    if (! $user || ! Hash::check($data['password'], $user->password)) {
      throw ValidationException::withMessages([
        'email' => ['Kredensial yang diberikan salah.'],
      ]);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return ['user' => $user, 'token' => $token];
  }

  public function logout(): void
  {
    auth()->user()->currentAccessToken()->delete();
  }
}
