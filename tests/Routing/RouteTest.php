<?php

namespace Dingo\Api\Tests\Routing;

use Dingo\Api\Http\Request;
use Dingo\Api\Routing\Route;
use Dingo\Api\Tests\BaseTestCase;
use Dingo\Api\Tests\Stubs\BasicThrottleStub;
use Dingo\Api\Tests\Stubs\RoutingAdapterStub;
use Dingo\Api\Tests\Stubs\RoutingControllerStub;
use Dingo\Api\Tests\Stubs\ThrottleStub;
use Illuminate\Container\Container;
use Illuminate\Routing\Route as IlluminateRoute;

class RouteTest extends BaseTestCase
{
    /**
     * @var RoutingAdapterStub
     */
    protected $adapter;
    /**
     * @var Container
     */
    protected $container;

    public function setUp(): void
    {
        $this->container = new Container;
        $this->adapter = new RoutingAdapterStub($this->container);
    }

    public function testCreatingNewRoute()
    {
        $request = Request::create('foo', 'GET');

        $route = new Route($this->adapter, $this->container, $request, new IlluminateRoute(['GET', 'HEAD'], 'foo', [
            'scopes' => ['foo', 'bar'],
            'providers' => ['foo'],
            'limit' => 5,
            'expires' => 10,
            'throttle' => BasicThrottleStub::class,
            'version' => ['v1'],
            'conditionalRequest' => false,
            'middleware' => 'foo.bar',
        ]));

        $this->assertSame(['foo', 'bar'], $route->scopes(), 'Route did not setup scopes correctly.');
        $this->assertSame(['foo'], $route->getAuthenticationProviders(), 'Route did not setup authentication providers correctly.');
        $this->assertSame(5, $route->getRateLimit(), 'Route did not setup rate limit correctly.');
        $this->assertSame(10, $route->getRateLimitExpiration(), 'Route did not setup rate limit expiration correctly.');
        $this->assertTrue($route->hasThrottle(), 'Route did not setup throttle correctly.');
        $this->assertInstanceOf(BasicThrottleStub::class, $route->getThrottle(), 'Route did not setup throttle correctly.');
        $this->assertFalse($route->requestIsConditional(), 'Route did not setup conditional request correctly.');
    }

    public function testControllerOptionsMergeAndOverrideRouteOptions()
    {
        $request = Request::create('foo', 'GET');

        $route = new Route($this->adapter, $this->container, $request, new IlluminateRoute(['GET', 'HEAD'], 'foo', [
            'scopes' => ['foo', 'bar'],
            'providers' => ['foo'],
            'limit' => 5,
            'expires' => 10,
            'throttle' => ThrottleStub::class,
            'version' => ['v1'],
            'conditionalRequest' => false,
            'uses' => RoutingControllerStub::class.'@index',
            'middleware' => 'foo.bar',
        ]));

        $this->assertSame(['foo', 'bar', 'baz', 'bing'], $route->scopes(), 'Route did not setup scopes correctly.');
        $this->assertSame(['foo', 'red', 'black'], $route->getAuthenticationProviders(), 'Route did not setup authentication providers correctly.');
        $this->assertSame(10, $route->getRateLimit(), 'Route did not setup rate limit correctly.');
        $this->assertSame(20, $route->getRateLimitExpiration(), 'Route did not setup rate limit expiration correctly.');
        $this->assertTrue($route->hasThrottle(), 'Route did not setup throttle correctly.');
        $this->assertInstanceOf(BasicThrottleStub::class, $route->getThrottle(), 'Route did not setup throttle correctly.');

        $route = new Route($this->adapter, $this->container, $request, new IlluminateRoute(['GET', 'HEAD'], 'foo/bar', [
            'scopes' => ['foo', 'bar'],
            'providers' => ['foo'],
            'limit' => 5,
            'expires' => 10,
            'throttle' => ThrottleStub::class,
            'version' => ['v1'],
            'conditionalRequest' => false,
            'uses' => RoutingControllerStub::class.'@show',
        ]));

        $this->assertSame(['foo', 'bar', 'baz', 'bing', 'bob'], $route->scopes(), 'Route did not setup scopes correctly.');
        $this->assertSame(['foo'], $route->getAuthenticationProviders(), 'Route did not setup authentication providers correctly.');
        $this->assertSame(10, $route->getRateLimit(), 'Route did not setup rate limit correctly.');
        $this->assertSame(20, $route->getRateLimitExpiration(), 'Route did not setup rate limit expiration correctly.');
        $this->assertTrue($route->hasThrottle(), 'Route did not setup throttle correctly.');
        $this->assertInstanceOf(BasicThrottleStub::class, $route->getThrottle(), 'Route did not setup throttle correctly.');
    }
}
