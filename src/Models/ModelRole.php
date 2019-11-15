<?php

namespace NeelBhanushali\LaravelRolesAndPermissions\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class ModelRole extends MorphPivot
{
    protected $table = 'model_roles';
}
