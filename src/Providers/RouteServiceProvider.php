<?php

namespace Laravelha\Auth\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Laravelha\Auth\Http\Middleware\Authorize;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Relative path to the root
     */
    private const ROOT_PATH = __DIR__ . '/../..';

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Laravelha\Auth\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->aliasMiddleware('ha.acl', Authorize::class);

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(): void
    {
        $this->mapApiRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/auth')
             ->as('api.auth.')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(self::ROOT_PATH . '/routes/api.php');
    }
}
