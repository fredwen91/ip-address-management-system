<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\IpAddressController;
use App\Http\Middleware\InternalApiMiddleware;
use App\Http\Middleware\ValidateUserToken;
use Illuminate\Support\Facades\Route;

Route::post('/audit_logs', [AuditLogController::class, 'store'])->middleware(InternalApiMiddleware::class);

Route::middleware([ValidateUserToken::class])->group(function () {
    Route::apiResource('ip_addresses', IpAddressController::class);

    Route::get('audit_logs', [AuditLogController::class, 'index']);
});
