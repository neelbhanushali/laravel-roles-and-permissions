<?php

namespace NeelBhanushali\LaravelRolesAndPermissions\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class ModelPermission extends MorphPivot
{
    protected $table = 'model_permissions';
}
