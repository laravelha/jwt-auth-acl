<?php

namespace Laravelha\Auth\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class Authorize
{
    use AuthorizesRequests;

    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next)
    {
        $this->authorize($request->route()->getName());

        return $next($request);
    }
}
