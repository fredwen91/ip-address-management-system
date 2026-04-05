<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class AuditLogService
{
    public function getAll($request): LengthAwarePaginator
    {
        $perPage = $request->integer('itemsPerPage', 10);

        $auditLogs = AuditLog::query()
            ->select('*')
            ->search($request->search)
            ->sort($request->sortKey, $request->sortOrder)
            ->paginate($perPage)
            ->withQueryString();

        $userIds = $auditLogs->pluck('user_id')->unique()->values();

        $users = Http::withHeaders([
            'X-INTERNAL-KEY' => config('myconfig.internal_api_key')
        ])->post(config('myconfig.auth_service_url') . '/api/users/bulk', [
            'user_ids' => $userIds
        ])->json();

        $userMap = collect($users)->keyBy('id');

        $auditLogs->transform(function ($ip) use ($userMap) {
            $ip->user_name = $userMap[$ip->user_id]['name'] ?? null;
            return $ip;
        });

        return $auditLogs;
    }

    public static function log(array $data): void
    {
        AuditLog::create([
            'user_id' => $data['user_id'],
            'action' => $data['action'],
            'entity_type' => $data['entity_type'],
            'entity_id' => $data['entity_id'] ?? null,
            'changes' => isset($data['changes']) ? json_encode($data['changes']) : null,
            'session_id' => $data['session_id'],
        ]);
    }
}
