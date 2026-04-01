<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditLogService
{
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
