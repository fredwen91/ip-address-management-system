<?php

namespace App\Models;

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
}
