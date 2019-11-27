<?php

namespace NeelBhanushali\LaravelRolesAndPermissions\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        foreach ($permissions as $permission) {
            if (!$request->user()->hasPermission($permission)) {
                throw new AuthorizationException;
            }
        }

        return $next($request);
    }
}
