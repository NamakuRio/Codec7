<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $permission = null)
    {
        if (!$request->user()->hasRole($role)) {
            return view('pages.not-authorized');
        }

        if ($permission !== null && !$request->user()->can($permission)) {
            return Response::view('pages.not-authorized');
        }

        return $next($request);
    }
}
