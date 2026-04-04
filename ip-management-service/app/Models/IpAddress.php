<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class IpAddress extends Model
{
    protected $fillable = ['ip_address', 'label', 'comment', 'user_id', 'user_name'];

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('ip_address', 'like', "%{$search}%")
                ->orWhere('label', 'like', "%{$search}%");
        });
    }

    public function scopeSort(Builder $query, ?string $sortKey, ?string $sortOrder): Builder
    {
        $allowedSorts = [
            'ip_address',
            'label',
            'comment',
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
