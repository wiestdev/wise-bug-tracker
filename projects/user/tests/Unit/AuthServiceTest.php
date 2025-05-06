<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthService $service;

    protected function setUp(): void
    {
        parent::setUp();
        // В Laravel 12 фасады автоматически настроены
        $this->service = $this->app->make(AuthService::class);
    }

    #[Test]
    public function register_creates_user_and_returns_user_and_token(): void
    {
        $data = [
            'name'     => 'Unit Tester',
            'email'    => 'unit@example.com',
            'password' => 'secret123',
        ];

        ['user' => $user, 'token' => $token] = $this->service->register($data);

        // Проверяем, что пользователь в базе
        $this->assertDatabaseHas('users', [
            'email' => 'unit@example.com',
        ]);

        // Проверяем возвращённые данные
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('unit@example.com', $user->email);
        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }

    #[Test]
    public function login_returns_token_for_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('mypassword'),
        ]);

        $token = $this->service->login([
            'email'    => $user->email,
            'password' => 'mypassword',
        ]);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);

        // Убедимся, что токен действительно валиден и содержит sub = $user->id
        $payload = JWTAuth::setToken($token)->getPayload();
        $this->assertEquals($user->id, $payload->get('sub'));
    }

    #[Test]
    public function login_returns_null_for_invalid_credentials(): void
    {
        User::factory()->create(['email' => 'foo@bar.com', 'password' => bcrypt('rightpass')]);

        $token = $this->service->login([
            'email'    => 'foo@bar.com',
            'password' => 'wrongpass',
        ]);

        $this->assertSame('', $token);
    }

    #[Test]
    public function me_returns_current_user(): void
{
    $user  = User::query()->create([
        'name'     => 'C',
        'email'    => 'c@d.e',
        'password' => Hash::make('pass'),
    ]);

    $token = JWTAuth::fromUser($user);
    JWTAuth::setToken($token);

    // ⬇️ авторизуем пользователя вручную
    auth()->setUser($user);

    $me = $this->service->me();

    $this->assertInstanceOf(User::class, $me);
    $this->assertSame($user->id, $me->id);
}


    #[Test]
    public function refresh_returns_new_token(): void
    {
        $user  = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        JWTAuth::setToken($token);
        $newToken = $this->service->refresh();

        $this->assertIsString($newToken);
        $this->assertNotSame($token, $newToken);
    }

    #[Test]
    public function logout_invalidates_token(): void
    {
        $user  = User::query()->create([
            'name'     => 'E',
            'email'    => 'e@f.g',
            'password' => Hash::make('pass'),
        ]);

        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);

        $this->service->logout();

        // очистить текущее состояние фасада
        JWTAuth::unsetToken();

        // теперь попытка использовать старый токен должна выбросить TokenInvalidException
        $this->expectException(\Tymon\JWTAuth\Exceptions\TokenInvalidException::class);

        JWTAuth::setToken($token)->getPayload();
    }
}