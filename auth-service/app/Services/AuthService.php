<?php

namespace App\Services;

use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthService
{
    public function login(array $credentials): array
    {
        if (!$token = Auth::attempt($credentials)) {
            throw new UnauthorizedHttpException('', 'Invalid credentials');
        }

        $user = Auth::user();
        $refreshToken = $this->createRefreshToken($user);

        Http::withHeaders([
            'X-INTERNAL-KEY' => config('myconfig.internal_api_key')
        ])->post(config('myconfig.ip_management_service_url') . '/api/audit_logs', [
            'user_id' => $user->id,
            'action' => 'login',
            'entity_type' => 'auth',
            'session_id' => session()->getId(),
        ]);

        return [
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'user' => $user
        ];
    }

    public function me(): User
    {
        return Auth::user();
    }

    public function refresh(array $refreshToken): array
    {
        $token = RefreshToken::where('token', $refreshToken)->first();

        if (!$token || !$token->isValid()) {
            throw new UnauthorizedHttpException('', 'Invalid refresh token');
        }

        // revoke old token
        $token->update(['revoked_at' => now()]);

        $user = $token->user;

        // generate new access token
        $accessToken = Auth::login($user);

        // generate new refresh token
        $newRefreshToken = $this->createRefreshToken($user);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $newRefreshToken
        ];
    }

    public function logout(array $refreshToken): void
    {
        RefreshToken::where('token', $refreshToken)
            ->update(['revoked_at' => now()]);

        Http::withHeaders([
            'X-INTERNAL-KEY' => config('myconfig.internal_api_key')
        ])->post(config('myconfig.ip_management_service_url') . '/api/audit_logs', [
            'user_id' => Auth::user()->id,
            'action' => 'logout',
            'entity_type' => 'auth',
            'session_id' => session()->getId(),
        ]);

        Auth::logout();
    }

    private function createRefreshToken(User $user): string
    {
        return RefreshToken::create([
            'user_id' => $user->id,
            'token' => hash('sha256', Str::random(64)),
            'expires_at' => now()->addDays(7)
        ])->token;
    }
}
