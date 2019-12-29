# laravel-roles-and-permissions

use `NeelBhanushali\LaravelRolesAndPermissions\Traits\HasRolesAndPermissions` trait.

# relations
* roles
* permissions
* entity

# methods
* getRoles()
* getPermissions()
* hasRole($role_name)
* hasPermission($permission_name)

# roles/permissions table have following columns
* entity_id => nullable
* entity_type => nullable
* is_visible_to_users => default=1

# Notes
* add following to `app/Http/Kernel.php` file
```
'role' => \NeelBhanushali\LaravelRolesAndPermissions\Middleware\Role::class,
'permission' => \NeelBhanushali\LaravelRolesAndPermissions\Middleware\Permission::class,
'role-or-permission' => \NeelBhanushali\LaravelRolesAndPermissions\Middleware\RoleOrPermission::class,
```
