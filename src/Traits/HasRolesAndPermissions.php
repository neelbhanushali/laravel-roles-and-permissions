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
        $permissions_from_roles = [];
        foreach ($this->roles as $role) {
            foreach ($role->getPermissions() as $permission) {
                $permissions_from_roles[] = $permission;
            }
        }
        $permissions_from_roles = array_values(array_unique($permissions_from_roles));

        $model_permissions = [];
        foreach ($this->permissions as $permission) {
            if (!$permission->pivot->is_revoked) {
                $model_permissions[] = $permission->name;
            }
        }

        $all_permissions = array_merge($permissions_from_roles, $model_permissions);

        foreach ($this->permissions()->where('is_revoked', 1)->get() as $revoked_permission) {
            $all_permissions = array_filter($all_permissions, function ($permission) use ($revoked_permission) {
                return $permission != $revoked_permission->name;
            });
        }

        return array_values($all_permissions);
    }
}
