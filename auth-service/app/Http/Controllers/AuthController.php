<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());

        return response()->json($data);
    }

    public function me()
    {
        return response()->json($this->authService->me());
    }

    public function refresh(RefreshTokenRequest $request)
    {
        $data = $this->authService->refresh($request->validated());

        return response()->json($data);
    }

    public function logout(RefreshTokenRequest $request)
    {
        $this->authService->logout($request->validated());

        return response()->json(['message' => 'Logged out']);
    }
}
