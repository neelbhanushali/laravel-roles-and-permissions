<?php

namespace NeelBhanushali\LaravelRolesAndPermissions\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class RoleOrPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$params)
    {
        $roles = $request->user()->getRoles();
        $permissions = $request->user()->getPermissions();
        $all = collect($roles)->merge($permissions);

        foreach ($params as $param) {
            if (!$all->contains($param)) {
                throw new AuthorizationException;
            }
        }

        return $next($request);
    }
}
