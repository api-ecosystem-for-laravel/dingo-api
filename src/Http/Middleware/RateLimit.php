<?php

namespace Dingo\Api\Http\Middleware;

use Closure;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Router;
use Dingo\Api\Http\InternalRequest;
use Dingo\Api\Http\RateLimit\Handler;
use Dingo\Api\Exception\RateLimitExceededException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RateLimit
{
    /**
     * Perform rate limiting before a request is executed.
     *
     * @param \Dingo\Api\Http\Request $request
     * @param Closure                 $next
     *
     * @return mixed
     *
     * @throws HttpException
     */
    public function handle($request, Closure $next)
    {
        if ($request instanceof InternalRequest) {
            return $next($request);
        }

        $route   = app(Router::class)->getCurrentRoute();
        $handler = app(Handler::class);

        if ($route->hasThrottle()) {
            $handler->setThrottle($route->getThrottle());
        }

        $handler->rateLimitRequest($request, $route->getRateLimit(), $route->getRateLimitExpiration());

        if ($handler->exceededRateLimit()) {
            throw new RateLimitExceededException('You have exceeded your rate limit.', null, $this->getHeaders());
        }

        $response = $next($request);

        if ($handler->requestWasRateLimited()) {
            return $this->responseWithHeaders($response);
        }

        return $response;
    }

    /**
     * Send the response with the rate limit headers.
     *
     * @param Response $response
     *
     * @return Response
     */
    protected function responseWithHeaders($response)
    {
        foreach ($this->getHeaders() as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }

    /**
     * Get the headers for the response.
     *
     * @return array
     */
    protected function getHeaders()
    {
        $handler = app(Handler::class);

        return [
            'X-RateLimit-Limit'     => $handler->getThrottleLimit(),
            'X-RateLimit-Remaining' => $handler->getRemainingLimit(),
            'X-RateLimit-Reset'     => $handler->getRateLimitReset(),
        ];
    }
}
