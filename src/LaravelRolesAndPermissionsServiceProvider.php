<?php

namespace NeelBhanushali\LaravelRolesAndPermissions;

use Illuminate\Support\ServiceProvider;

class LaravelRolesAndPermissionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations')
        ], 'laravel-roles-and-permissions.migrations');
    }
}
