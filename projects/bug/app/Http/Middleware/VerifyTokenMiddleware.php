<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyTokenMiddleware
{
    public function __construct(
        private readonly AuthService $auth,   // DI-контейнер Laravel 12
    ) {}

    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        // Bearer-строка без слова «Bearer »
        $token = $request->bearerToken();

        if (! $this->auth->verifyToken($token)) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        /** ↓ Опционально: кладём user_id в Request-атрибуты — пригодится в контроллерах */
        try {
            $userId = JWTAuth::setToken($token)->getPayload()->get('sub');
            $request->attributes->set('user_id', $userId);
        } catch (\Throwable) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return $next($request);
    }
}
