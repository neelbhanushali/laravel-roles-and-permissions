<?php

namespace NeelBhanushali\LaravelRolesAndPermissions\Traits;

use NeelBhanushali\LaravelRolesAndPermissions\Models\ModelPermission;
use NeelBhanushali\LaravelRolesAndPermissions\Models\ModelRole;
use NeelBhanushali\LaravelRolesAndPermissions\Models\Permission;
use NeelBhanushali\LaravelRolesAndPermissions\Models\Role;

trait HasRolesAndPermissions
{
    public function roles()
    {
        return $this->morphToMany(Role::class, 'entity', 'model_roles', null, 'role_id')
            ->withTimestamps()
            ->using(ModelRole::class)
            ->withoutGlobalScope('global');
    }

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'entity', 'model_permissions', null, 'permission_id')
            ->withTimestamps()
            ->withPivot('is_revoked')
            ->using(ModelPermission::class)
            ->withoutGlobalScope('global');
    }

    public function getRoles()
    {
        return $this->roles->pluck('name');
    }

    public function getPermissions()
    {
        $permissions_from_roles = collect([]);
        $this->roles->each(function ($r) use (&$permissions_from_roles) {
            $permissions_from_roles = $permissions_from_roles->merge($r->getPermissions());
        });

        $model_permissions = $this->permissions()->revoked(false)->pluck('name');

        $all_permissions = $permissions_from_roles
            ->merge($model_permissions)
            ->unique()->values();

        // removing revoked permissions
        $all_permissions = $all_permissions->diff($this->permissions()->revoked()->pluck('name'));

        return $all_permissions->unique()->values();
    }

    public function hasRole($role)
    {
        return $this->getRoles()->contains($role);
    }

    public function hasPermission($permission)
    {
        return $this->getPermissions()->contains($permission);
    }

    public function scopeRole($query, $role)
    {
        return $query->whereHas('roles', function ($query) use ($role) {
            $query->withoutGlobalScope('global')->where(function ($query) use ($role) {
                $query->where('name', $role);
            });
        });
    }

    public function scopePermission($query, $permission)
    {
        return $query->whereHas('permissions', function ($query) use ($permission) {
            $query->withoutGlobalScope('global')->where(function ($query) use ($permission) {
                $query->where('name', $permission);
            });
        });
    }
}
