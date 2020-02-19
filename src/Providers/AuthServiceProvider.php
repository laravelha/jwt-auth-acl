<?php

namespace Laravelha\Auth\Providers;

use Illuminate\Support\ServiceProvider;

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
        ], 'auth-seeds');
    }
}
