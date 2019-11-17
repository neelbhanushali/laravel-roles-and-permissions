<?php

namespace NeelBhanushali\LaravelRolesAndPermissions\Traits;

use NeelBhanushali\LaravelRolesAndPermissions\Models\ModelPermission;
use NeelBhanushali\LaravelRolesAndPermissions\Models\ModelRole;
use NeelBhanushali\LaravelRolesAndPermissions\Models\Permission;
use NeelBhanushali\LaravelRolesAndPermissions\Models\Role;

trait HasRolesAndPermissions
{
    public function model_roles()
    {
        return $this->morphToMany(Role::class, 'entity', 'model_roles', null, 'role_id')
            ->withTimestamps()
            ->using(ModelRole::class);
    }

    public function model_permissions()
    {
        return $this->morphToMany(Permission::class, 'entity', 'model_permissions', null, 'permission_id')
            ->withTimestamps()
            ->withPivot('is_revoked')
            ->using(ModelPermission::class);
    }
}
