<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $guarded = [];

    public function delete()
    {
        throw new \Exception('Audit logs cannot be deleted');
    }

    public function update(array $attributes = [], array $options = [])
    {
        throw new \Exception('Audit logs cannot be modified');
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('action', 'like', "%{$search}%")
                ->orWhere('entity_type', 'like', "%{$search}%");
        });
    }

    public function scopeSort(Builder $query, ?string $sortKey, ?string $sortOrder): Builder
    {
        $allowedSorts = [
            'action',
            'entity_type',
            'entity_id',
            'created_at',
            'updated_at',
        ];

        if (!in_array($sortKey, $allowedSorts)) {
            return $query->orderByDesc('created_at');
        }

        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sortKey, $sortOrder);
    }
}
