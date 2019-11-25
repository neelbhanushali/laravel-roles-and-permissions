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

    public function scopeGlobal($query, $is_global = true)
    {
        $query->where('is_global', $is_global ? 1 : 0);

        if ($is_global) {
            $query
                ->whereNull('entity_type')
                ->whereNull('entity_id');
        }

        return $query;
    }
}
