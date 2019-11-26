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
* Role/Permission models have global scope `visible` set, only pulling tuples that have `is_visible_to_users`=1
* to remove that global scope run `Model::withoutGlobalScope('visible')->get()`
* can use `invisible` scope that pulls tuples that have `is_visible_to_users`=0
