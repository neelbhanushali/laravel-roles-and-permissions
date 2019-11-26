<?php

namespace NeelBhanushali\LaravelRolesAndPermissions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Permission extends Model
{
    public function scopeGlobal($query)
    {
        return $query->whereNull('entity_id')->whereNull('entity_type');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('visible', function (Builder $builder) {
            $builder->where('is_visible_to_users', 1);
        });
    }

    public function scopeInvisible($query)
    {
        return $query->where('is_visible_to_users', 0);
    }
}
