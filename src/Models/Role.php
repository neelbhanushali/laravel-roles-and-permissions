<?php

namespace NeelBhanushali\LaravelRolesAndPermissions\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function getPermissions()
    {
        return $this->permissions->pluck('name');
    }
}
