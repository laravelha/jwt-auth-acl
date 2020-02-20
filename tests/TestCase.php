<?php

namespace Laravelha\Auth\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Gate;
use Laravelha\Auth\Models\Permission;
use Laravelha\Auth\Models\Role;
use Laravelha\Auth\Models\User;
use Laravelha\Auth\Providers\AuthServiceProvider;
use Laravelha\Auth\Providers\RouteServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use PermissionsTableSeeder;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Providers\LaravelServiceProvider as JWTAuthServiceProvider;

abstract class TestCase extends Orchestra
{
    protected $permissions;
    protected $role;
    protected $user;
    protected $headers;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->createPermissions();

        $this->headers = ['Authorization' => 'Bearer ' . JWTAuth::fromUser($this->user)];

        foreach ($this->permissions as $permission) {
            Gate::define($permission->verb . '|' . $permission->uri, function (User $user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }
    }

    /**
     * add the package provider
     *
     * @param  Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AuthServiceProvider::class,
            RouteServiceProvider::class,
            JWTAuthServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('jwt.secret', '4LcR6Z7HoYgfc4PVHntnomIigYoVlzkrbtxcgvFzqR0bgFhwlYJG4eODp3Swe2BN');
        $app['config']->set('auth.defaults.guard', 'api');
        $app['config']->set('auth.guards.api.driver', 'jwt');
        $app['config']->set('auth.providers.users.model', User::class);
    }

    /**
     * Create permissions
     */
    protected function createPermissions()
    {
        require_once './database/seeds/PermissionsTableSeeder.php';
        (new PermissionsTableSeeder())->run();

        $this->user = User::find(1);
        $this->role = Role::find(1);
        $this->permissions = Permission::all();
    }
}
