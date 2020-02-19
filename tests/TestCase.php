<?php

namespace Laravelha\Auth\Tests;

use Illuminate\Foundation\Application;
use Laravelha\Auth\Providers\AuthServiceProvider;
use Laravelha\Auth\Providers\RouteServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Tymon\JWTAuth\Providers\LaravelServiceProvider as JWTAuthServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
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
    }
}
