<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Middleware\InternalApiMiddleware;
use App\Http\Middleware\ValidateUserToken;
use Illuminate\Support\Facades\Route;

Route::post('/audit_logs', [AuditLogController::class, 'store'])->middleware(InternalApiMiddleware::class);

Route::middleware([ValidateUserToken::class])->group(function () {
    Route::get('/ip_addresses', function () {
        return response()->json([
            'message' => 'Test'
        ]);
    });

    Route::get('/ip_addresses/{id}', function () {
        return response()->json([
            'message' => 'Test1'
        ]);
    });
});
