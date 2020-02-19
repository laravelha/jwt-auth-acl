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

        $this->role = factory(Role::class)->create(['name' => 'admin']);
        $this->role->permissions()->attach($this->permissions);

        $this->user = factory(User::class)->create();
        $this->user->roles()->attach($this->role);

        $this->headers = ['Authorization' => 'Bearer ' . JWTAuth::fromUser($this->user)];

        foreach (Permission::all() as $permission) {
            Gate::define($permission->name, function (User $user) use ($permission) {
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
        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.refresh']);
        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.me']);

        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.permissions.index']);
        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.permissions.store']);
        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.permissions.show']);
        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.permissions.update']);
        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.permissions.destroy']);

        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.roles.index']);
        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.roles.store']);
        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.roles.show']);
        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.roles.update']);
        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.roles.destroy']);

        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.users.index']);
        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.users.store']);
        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.users.show']);
        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.users.update']);
        $this->permissions[] = factory(Permission::class)->create(['name' => 'api.auth.users.destroy']);
    }
}
