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
        return $this->morphToMany(Role::class, 'model', 'model_roles', null, 'role_id')
            ->withTimestamps()
            ->using(ModelRole::class);
    }

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'model', 'model_permissions', null, 'permission_id')
            ->withTimestamps()
            ->withPivot('is_revoked')
            ->using(ModelPermission::class);
    }

    public function getRoles($scope_type = null, $scope_id = null)
    {
        $scope_type = $scope_type ?? request('scope_type');
        $scope_id = $scope_id ?? request('scope_id');

        $roles = $this->roles()->where(compact('scope_id', 'scope_type'));

        return $roles->pluck('name');
    }

    public function getPermissions($scope_type = null, $scope_id = null)
    {
        $scope_type = $scope_type ?? request('scope_type');
        $scope_id = $scope_id ?? request('scope_id');

        $permissions_from_roles = collect([]);
        $this->roles->each(function ($r) use (&$permissions_from_roles) {
            $permissions_from_roles = $permissions_from_roles->merge($r->getPermissions());
        });

        $model_permissions = $this->permissions()
            ->where(compact('scope_id', 'scope_type'))
            ->revoked(false)
            ->pluck('name');

        $all_permissions = $permissions_from_roles
            ->merge($model_permissions)
            ->unique()->values();

        // removing revoked permissions
        $revoked_permissions = $this->permissions()
            ->where(compact('scope_id', 'scope_type'))
            ->revoked()
            ->pluck('name');
        $all_permissions = $all_permissions->diff($revoked_permissions);

        return $all_permissions->unique()->values();
    }

    public function hasRole($role, $scope_type = null, $scope_id = null)
    {
        return $this->getRoles($scope_type, $scope_id)->contains($role);
    }

    public function hasPermission($permission, $scope_type = null, $scope_id = null)
    {
        return $this->getPermissions($scope_type, $scope_id)->contains($permission);
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
