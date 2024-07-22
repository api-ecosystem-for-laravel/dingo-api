<?php

namespace Dingo\Api\Tests\Routing;

use Dingo\Api\Routing\Router;
use Dingo\Api\Routing\UrlGenerator;
use Dingo\Api\Tests\Stubs\RoutingAdapterStub;
use Dingo\Api\Tests\Stubs\RoutingControllerStub;
use Illuminate\Container\Container;

class UrlGeneratorTestAbstract extends Adapter\BaseAdapterTestAbstract
{
    public function getAdapterInstance()
    {
        return $this->container->make(RoutingAdapterStub::class);
    }

    public function getContainerInstance()
    {
        return new Container;
    }

    public function testRouteGenerationSimple()
    {
        $this->router = new Router($this->adapter, $this->exception, $this->container, null, null);
        $this->router->version('v1', function (Router $router) {
            $router->any('foo', function () {
            })->name('my.route');
        });

        $generator = new UrlGenerator($this->createRequest('/foo', 'GET'));
        $generator->setRouteCollections($this->router->getRoutes());

        $test = $generator->version('v1')->route('my.route');
        $this->assertSame('http://localhost/foo', $test);
    }

    public function testRouteGenerationWithActionAs()
    {
        $this->router = new Router($this->adapter, $this->exception, $this->container, null, null);
        $this->router->version('v1', function (Router $router) {
            $router->any('foo', [
                'as' => 'my.route',
                'uses' => RoutingControllerStub::class.'@index',
            ]);
        });

        $generator = new UrlGenerator($this->createRequest('/foo', 'GET'));
        $generator->setRouteCollections($this->router->getRoutes());

        $test = $generator->version('v1')->route('my.route');
        $this->assertSame('http://localhost/foo', $test);
    }

    public function testRouteGenerationWithDomain()
    {
        $this->router = new Router($this->adapter, $this->exception, $this->container, 'dingo.dev', null);
        $this->router->version('v1', function (Router $router) {
            $router->any('foo', function () {
            })->name('my.route');
        });

        $generator = new UrlGenerator($this->createRequest('/foo', 'GET'));
        $generator->setRouteCollections($this->router->getRoutes());

        $test = $generator->version('v1')->route('my.route');
        $this->assertSame('http://dingo.dev/foo', $test);
    }

    public function testRouteGenerationWithPrefix()
    {
        $this->router = new Router($this->adapter, $this->exception, $this->container, 'dingo.dev', 'api');
        $this->router->version('v1', function (Router $router) {
            $router->any('foo', function () {
            })->name('my.route');
        });

        $generator = new UrlGenerator($this->createRequest('/foo', 'GET'));
        $generator->setRouteCollections($this->router->getRoutes());

        $test = $generator->version('v1')->route('my.route');
        $this->assertSame('http://dingo.dev/api/foo', $test);
    }

    public function testRouteGenerationWithNamedParameters()
    {
        $this->router = new Router($this->adapter, $this->exception, $this->container, 'dingo.dev', 'api');
        $this->router->version('v1', function (Router $router) {
            $router->any('foo/{bar}', function () {
            })->name('my.route');
        });

        $generator = new UrlGenerator($this->createRequest('/foo', 'GET'));
        $generator->setRouteCollections($this->router->getRoutes());

        $test = $generator->version('v1')->route('my.route', ['bar' => 'xyz']);
        $this->assertSame('http://dingo.dev/api/foo/xyz', $test);
    }

    public function testRouteGenerationWithIndexedParameters()
    {
        $this->router = new Router($this->adapter, $this->exception, $this->container, 'dingo.dev', 'api');
        $this->router->version('v1', function (Router $router) {
            $router->any('foo/{bar}', function () {
            })->name('my.route');
        });

        $generator = new UrlGenerator($this->createRequest('/foo', 'GET'));
        $generator->setRouteCollections($this->router->getRoutes());

        $test = $generator->version('v1')->route('my.route', ['xyz']);
        $this->assertSame('http://dingo.dev/api/foo/xyz', $test);
    }
}
