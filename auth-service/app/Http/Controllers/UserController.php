<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function bulk(BulkUserRequest $request)
    {
        $users = $this->userService->getUsersByIds(
            $request->validated('user_ids')
        );

        return response()->json($users);
    }
}
