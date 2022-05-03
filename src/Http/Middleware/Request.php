<?php

namespace Dingo\Api\Http\Middleware;

use Closure;
use Dingo\Api\Contract\Debug\ExceptionHandler;
use Dingo\Api\Contract\Http\Request as RequestContract;
use Dingo\Api\Event\RequestWasMatched;
use Dingo\Api\Http\Request as HttpRequest;
use Dingo\Api\Http\RequestValidator;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Router;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler as LaravelExceptionHandler;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Pipeline\Pipeline;
use Laravel\Lumen\Application;

class Request
{
    /**
     * Array of middleware.
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if (app(RequestValidator::class)->validateRequest($request)) {
                app()->singleton(LaravelExceptionHandler::class, function ($app) {
                    return $app[ExceptionHandler::class];
                });

                $request = app()->make(RequestContract::class)->createFromIlluminate($request);

                app(EventDispatcher::class)->dispatch(new RequestWasMatched($request, app()));

                return $this->sendRequestThroughRouter($request);
            }
        } catch (Exception $exception) {
            app(ExceptionHandler::class)->report($exception);

            return app(ExceptionHandler::class)->handle($exception);
        }

        return $next($request);
    }

    /**
     * Send the request through the Dingo router.
     *
     * @param HttpRequest $request
     *
     * @return Response
     */
    protected function sendRequestThroughRouter(HttpRequest $request)
    {
        app()->instance('request', $request);

        return (new Pipeline(app()))->send($request)->through($this->middleware)->then(function ($request) {
            return app(Router::class)->dispatch($request);
        });
    }

    /**
     * Call the terminate method on middlewares.
     *
     * @return void
     */
    public function terminate($request, $response)
    {
        $request = app('request');
        if (!($request instanceof HttpRequest)) {
            return;
        }

        // Laravel's route middlewares can be terminated just like application
        // middleware, so we'll gather all the route middleware here.
        // On Lumen this will simply be an empty array as it does
        // not implement terminable route middleware.
        $middlewares = $this->gatherRouteMiddlewares($request);

        // Because of how middleware is executed on Lumen we'll need to merge in the
        // application middlewares now so that we can terminate them. Laravel does
        // not need this as it handles things a little more gracefully so it
        // can terminate the application ones itself.
        if (class_exists(Application::class, false)) {
            $middlewares = array_merge($middlewares, $this->middleware);
        }

        foreach ($middlewares as $middleware) {
            if ($middleware instanceof Closure) {
                continue;
            }

            [$name, $parameters] = $this->parseMiddleware($middleware);

            $instance = app()->make($name);

            if (method_exists($instance, 'terminate')) {
                $instance->terminate($request, $response);
            }
        }
    }

    /**
     * Parse a middleware string to get the name and parameters.
     *
     * @param string $middleware
     *
     * @return array
     * @author Taylor Otwell
     *
     */
    protected function parseMiddleware($middleware)
    {
        [$name, $parameters] = array_pad(explode(':', $middleware, 2), 2, []);

        if (is_string($parameters)) {
            $parameters = explode(',', $parameters);
        }

        return [$name, $parameters];
    }

    /**
     * Gather the middlewares for the route.
     *
     * @param HttpRequest $request
     *
     * @return array
     */
    protected function gatherRouteMiddlewares($request)
    {
        $route = $request->route();
        if (null !== $route) {
            return app(Router::class)->gatherRouteMiddlewares($route);
        }

        return [];
    }

    /**
     * Set the middlewares.
     *
     * @param array $middleware
     *
     * @return void
     */
    public function setMiddlewares(array $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * Merge new middlewares onto the existing middlewares.
     *
     * @param array $middleware
     *
     * @return void
     */
    public function mergeMiddlewares(array $middleware)
    {
        $this->middleware = array_merge($this->middleware, $middleware);
    }
}
