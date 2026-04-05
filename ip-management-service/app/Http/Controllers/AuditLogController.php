<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuditLogRequest;
use App\Http\Requests\IndexRequest;
use App\Services\AuditLogService;

class AuditLogController extends Controller
{
    public function __construct(private AuditLogService $authService) {}

    public function index(IndexRequest $request)
    {
        $auditLogs = $this->authService->getAll($request);

        return response()->json($auditLogs);
    }

    public function store(AuditLogRequest $request)
    {
        $this->authService->log($request->validated());

        return response()->json(['status' => 'logged']);
    }
}
