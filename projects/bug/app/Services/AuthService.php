<?php


namespace App\Services;

class AuthService
{
    /** Проверка токена */
    public function verifyToken(string $accessToken): bool
    {
        $verifyResult = true; // do post request (127.0.0.1:8000/api/me) with accessToken in header
        return $verifyResult;
    }
}
