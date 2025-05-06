<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;

class AuthService
{
    /**
     * Проверяет Bearer-токен в user-service.
     *
     * @param  string|null  $accessToken  строка без слова «Bearer»
     */
    public function verifyToken(?string $accessToken): bool
    {
        if (blank($accessToken)) {
            return false;
        }

        $url = 'http://user-service:8000/api/auth/me';

        try {
            $resp = Http::timeout(3)
                ->withToken($accessToken)
                ->acceptJson()
                ->get($url);

            return $resp->successful();      // HTTP 200 → true
        } catch (\Throwable $e) {
            // user-service недоступен / таймаут
            return false;
        }
    }
}
