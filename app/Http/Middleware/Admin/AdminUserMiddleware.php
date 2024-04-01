<?php

namespace App\Http\Middleware\Admin;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::find($request->user()->id);
        if(!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => __('auth.not_allowed')
            ], 401);
        }

        return $next($request);
    }
}
