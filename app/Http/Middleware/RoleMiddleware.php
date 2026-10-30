<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }

    public function handle(Request $request,Closure $next,string ...$roles){
        $user = $request->user();
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        if (!in_array($user->role, $roles, true)) {
            abort(403, 'You are not authorized to access this page.');
        }
        
        return $next($request);
    }
}
