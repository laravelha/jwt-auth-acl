<?php

namespace Laravelha\Auth\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravelha\Auth\Models\Permission;
use Laravelha\Auth\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Relative path to the root
     */
    private const ROOT_PATH = __DIR__ . '/../..';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(self::ROOT_PATH . '/database/migrations');
        $this->loadFactoriesFrom(self::ROOT_PATH . '/database/factories');

        $this->registerPermissions();

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        $this->publishes([
            self::ROOT_PATH . '/stubs/config/auth.stub' => config_path('auth.php'),
        ], 'ha-auth-config');

        $permissionSeederPath = 'seeds/PermissionsTableSeeder.php';
        $this->publishes([
            self::ROOT_PATH . '/database/' . $permissionSeederPath => database_path($permissionSeederPath),
        ], 'ha-auth-seeds');
    }

    /**
     * Define Permissions with Gate
     *
     * @return void
     */
    protected function registerPermissions()
    {
        try {
            Schema::hasTable('permissions');

            foreach (Permission::all() as $permission) {
                Gate::define($permission->verb . '|' . $permission->uri, function (User $user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        } catch (\Exception $exception) {
            return;
        }
    }
}
