<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user;

        if (!$user || $user['role'] !== 'super_admin') {
            return response()->json([
                'error' => 'Forbidden. Super admin access only.'
            ], 403);
        }

        return $next($request);
    }
}
