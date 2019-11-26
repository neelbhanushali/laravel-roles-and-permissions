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
            ->using(ModelRole::class);
    }

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'entity', 'model_permissions', null, 'permission_id')
            ->withTimestamps()
            ->withPivot('is_revoked')
            ->using(ModelPermission::class);
    }

    public function getRoles()
    {
        return $this->roles->pluck('name');
    }

    public function getPermissions()
    {
        $permissions_from_roles = collect([]);
        foreach ($this->roles as $role) {
            foreach ($role->getPermissions() as $permission) {
                $permissions_from_roles->push($permission);
            }
        }
        $permissions_from_roles = $permissions_from_roles->unique()->values();

        $model_permissions = collect([]);
        foreach ($this->permissions as $permission) {
            if (!$permission->pivot->is_revoked) {
                $model_permissions->push($permission->name);
            }
        }

        $all_permissions = collect([])->merge($permissions_from_roles)->merge($model_permissions);

        foreach ($this->permissions()->where('is_revoked', 1)->get() as $revoked_permission) {
            $all_permissions = $all_permissions->filter(function ($permission) use ($revoked_permission) {
                return $permission != $revoked_permission->name;
            });
        }

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
            $query->where(function ($query) use ($role) {
                $query->where('name', $role);
            });
        });
    }

    public function scopePermission($query, $permission)
    {
        return $query->whereHas('permissions', function ($query) use ($permission) {
            $query->where(function ($query) use ($permission) {
                $query->where('name', $permission);
            });
        });
    }
}
