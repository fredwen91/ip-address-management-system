<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AuditLogService
{
    public function getAll($request): LengthAwarePaginator
    {
        $perPage = $request->integer('itemsPerPage', 10);

        return AuditLog::query()
            ->select('*')
            ->search($request->search)
            ->sort($request->sortKey, $request->sortOrder)
            ->paginate($perPage)
            ->withQueryString();
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
