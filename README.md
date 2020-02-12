# laravel-roles-and-permissions

use `NeelBhanushali\LaravelRolesAndPermissions\Traits\HasRolesAndPermissions` trait.

# relations
* roles
* permissions

# methods
* getRoles($scope_type = null, $scope_id = null)
* getPermissions($scope_type = null, $scope_id = null)
* hasRole($role_name, $scope_type = null, $scope_id = null)
* hasPermission($permission_name, $scope_type = null, $scope_id = null)

# Notes
* add following to `app/Http/Kernel.php` file
```
'roles' => \NeelBhanushali\LaravelRolesAndPermissions\Middleware\Role::class,
'permissions' => \NeelBhanushali\LaravelRolesAndPermissions\Middleware\Permission::class,
```
