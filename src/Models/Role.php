<?php

namespace NeelBhanushali\LaravelRolesAndPermissions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withoutGlobalScope('global');
    }

    public function getPermissions()
    {
        return $this->permissions->pluck('name');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('global', function (Builder $builder) {
            $builder->where('is_global', 1);
        });
    }
}
