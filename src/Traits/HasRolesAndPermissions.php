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

    public function getRoles($related_type = null, $related_id = null)
    {
        $model = $related_type ?? request('related_type');
        $id = $related_id ?? request('related_id');

        $roles = $this->roles()
            ->where(function ($query) use ($id, $model) {
                $query
                    ->where(function ($query) {
                        $query->whereNull('related_id')
                            ->whereNull('related_type');
                    })
                    ->orWhere(function ($query) use ($id, $model) {
                        $query->where('related_id', $id)
                            ->where('related_type', $model);
                    });
            });

        return $roles->pluck('name');
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
