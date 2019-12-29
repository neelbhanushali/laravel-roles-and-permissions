<?php

namespace NeelBhanushali\LaravelRolesAndPermissions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    public function scopeRevoked($query, $revoked = true)
    {
        return $query->where('is_revoked', $revoked ? 1 : 0);
    }
}
