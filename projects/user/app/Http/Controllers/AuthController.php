<?php


namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $service) {}

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|string|email|unique:users',
            'password'              => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return response()->json($this->service->register($request->all()), 201);
    }

    public function login(Request $request)
    {
        $token = $this->service->login($request->only('email', 'password'));
        return $token
            ? response()->json(compact('token'))
            : response()->json(['error' => 'Invalid credentials'], 401);
    }

    public function me()
    {
        return response()->json($this->service->me());
    }

    public function logout()
    {
        $this->service->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        $token = $this->service->refresh();
        return response()->json(compact('token'));
    }

    public function test()
    {
        return response()->json(['message' => 'Test route is working!']);
    }
}
