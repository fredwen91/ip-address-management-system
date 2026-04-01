<?php

use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;

Route::post('/audit_logs', [AuditLogController::class, 'store'])->middleware('internal.api');
