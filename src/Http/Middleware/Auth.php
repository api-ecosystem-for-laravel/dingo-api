<?php

namespace Dingo\Api\Http\Middleware;

use Closure;
use Dingo\Api\Routing\Router;
use Dingo\Api\Auth\Auth as Authentication;

class Auth
{
    /**
     * Perform authentication before a request is executed.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $route = app()->get(Router::class)->getCurrentRoute();
        $auth  = app()->get(Authentication::class);

        if (!$auth->check(false)) {
            $auth->authenticate($route->getAuthenticationProviders());
        }

        return $next($request);
    }
}
