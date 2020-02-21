<?php

namespace Laravelha\Auth\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\ServiceProvider;
use Laravelha\Auth\Facades\Abilities;
use Laravelha\Auth\Models\Permission;
use Laravelha\Auth\Services\AbilitiesService;

class AuthServiceProvider extends ServiceProvider implements DeferrableProvider
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
     * Register the package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ha.abilities', function () {
            return new AbilitiesService(Permission::all());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [AbilitiesService::class];
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
}
