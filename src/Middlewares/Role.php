<?php

namespace NeelBhanushali\LaravelRolesAndPermissions\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        foreach ($roles as $role) {
            if (!$request->user()->hasRole($role)) {
                throw new AuthorizationException;
            }
        }

        return $next($request);
    }
}
