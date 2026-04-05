<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::any('auth/{authEndpoint}', function ($authEndpoint) {
    $method = request()->method();

    $headers = ['Accept' => 'application/json'];

    if (in_array($authEndpoint, ['logout', 'me'])) {
        $headers['Authorization'] = request()->header('Authorization');
    }

    $options = ['query' => request()->query()];

    if (request()->isJson()) {
        $options['json'] = request()->json()->all();
    } else {
        $options['form_params'] = request()->all();
    }

    try {
        $response = Http::withHeaders($headers)
            ->send($method, config('myconfig.auth_service_url') . '/api/auth/' . $authEndpoint, $options);

        return response()->json($response->json(), $response->status());
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Auth service unavailable',
            'error' => $e->getMessage()
        ], 500);
    }
})->whereIn('authEndpoint', ['login', 'logout', 'me', 'refresh']);

Route::any('/{endpoits}/{any?}', function ($endpoits, $any = null) {
    $method = request()->method();
    $headers = [
        'Accept' => 'application/json',
        'Authorization' => request()->header('Authorization')
    ];

    $options = ['query' => request()->query()];

    if (request()->isJson()) {
        $options['json'] = request()->json()->all();
    } else {
        $options['form_params'] = request()->all();
    }

    try {
        $response = Http::withHeaders($headers)
            ->send($method, config('myconfig.ip_management_service_url') . '/api/' . $endpoits . '/' . $any, $options);

        return response()->json($response->json(), $response->status());
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'IP service unavailable',
            'error' => $e->getMessage()
        ], 500);
    }
});
