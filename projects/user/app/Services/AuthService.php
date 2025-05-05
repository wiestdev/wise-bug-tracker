<?php


namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthService
{
    /** Регистрация пользователя и выдача токена */
    public function register(array $data): array
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = JWTAuth::fromUser($user);
        return compact('user', 'token');
    }

    /** Аутентификация и выдача токена */
    public function login(array $credentials): string|null
    {
        return JWTAuth::attempt($credentials);
    }

    /** Получить текущего пользователя */
    public function me(): Authenticatable|null
    {
        return auth()->user();
    }

    /** Инвалидация токена */
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    /** Обновить токен */
    public function refresh(): string
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }
}
