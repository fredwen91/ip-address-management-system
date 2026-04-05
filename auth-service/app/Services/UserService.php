<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getUsersByIds(array $userIds)
    {
        return User::query()
            ->whereIn('id', $userIds)
            ->select(['id', 'name'])
            ->get();
    }
}
