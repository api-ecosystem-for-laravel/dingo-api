<?php

namespace Dingo\Api\Tests\Http\Middleware;

use Dingo\Api\Contract\Debug\ExceptionHandler;
use Dingo\Api\Contract\Http\Request as RequestContract;
use Dingo\Api\Exception\Handler;
use Dingo\Api\Http\Middleware\Request as RequestMiddleware;
use Dingo\Api\Http\Parser\Accept as AcceptParser;
use Dingo\Api\Http\Request;
use Dingo\Api\Http\RequestValidator;
use Dingo\Api\Http\Validation;
use Dingo\Api\Http\Validation\Accept;
use Dingo\Api\Http\Validation\Domain;
use Dingo\Api\Http\Validation\Prefix;
use Dingo\Api\Routing\Router;
use Dingo\Api\Tests\BaseTestCase;
use Dingo\Api\Tests\ChecksLaravelVersionTrait;
use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Events\Dispatcher as EventDispatcher;
use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Http\Response;
use Mockery as m;

class RequestTest extends BaseTestCase
{
    /**
     * @var \Dingo\Api\Tests\Stubs\Application58Stub|\Dingo\Api\Tests\Stubs\Application6Stub|\Dingo\Api\Tests\Stubs\ApplicationStub
     */
    protected $app;
    /**
     * @var Router|m\LegacyMockInterface|m\MockInterface
     */
    protected $router;
    /**
     * @var RequestValidator
     */
    protected $validator;
    /**
     * @var Handler|m\LegacyMockInterface|m\MockInterface
     */
    protected $handler;
    /**
     * @var EventDispatcher
     */
    protected $dispatcher;
    /**
     * @var RequestMiddleware
     */
    protected $middleware;

    use ChecksLaravelVersionTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->app        = $this->getApplicationStub();
        $this->router     = m::mock(Router::class);
        $this->validator  = new RequestValidator($this->app);
        $this->handler    = m::mock(Handler::class);
        $this->dispatcher = new EventDispatcher($this->app);

        $this->app->alias(Request::class, RequestContract::class);

        $this->middleware = new RequestMiddleware();

        Container::setInstance($this->app);
        app()->instance(Router::class, $this->router);
        app()->instance(RequestValidator::class, $this->validator);
        app()->instance(ExceptionHandler::class, $this->handler);
        app()->instance(DispatcherContract::class, $this->dispatcher);
    }

    public function testNoPrefixOrDomainDoesNotMatch()
    {
        $this->app[Domain::class] = new Validation\Domain(null);
        $this->app[Prefix::class] = new Validation\Prefix(null);
        $this->app[Accept::class] = new Validation\Accept(new AcceptParser('vnd', 'api', 'v1', 'json'));

        $request = Request::create('foo', 'GET');

        $this->middleware->handle($request, function ($handled) use ($request) {
            $this->assertSame($handled, $request);
        });
    }

    public function testPrefixMatchesAndSendsRequestThroughRouter()
    {
        $this->app[Domain::class] = new Validation\Domain(null);
        $this->app[Prefix::class] = new Validation\Prefix('/');
        $this->app[Accept::class] = new Validation\Accept(new AcceptParser('vnd', 'api', 'v1', 'json'));

        $request = IlluminateRequest::create('foo', 'GET');

        $this->router->shouldReceive('dispatch')->once();

        $this->middleware->handle($request, function () {
            //
        });

        $this->app[Domain::class] = new Validation\Domain(null);
        $this->app[Prefix::class] = new Validation\Prefix('bar');
        $this->app[Accept::class] = new Validation\Accept(new AcceptParser('vnd', 'api', 'v1', 'json'));

        $request = IlluminateRequest::create('bar/foo', 'GET');

        $this->router->shouldReceive('dispatch')->once();

        $this->middleware->handle($request, function () {
            //
        });

        $request = IlluminateRequest::create('bing/bar/foo', 'GET');

        $this->middleware->handle($request, function ($handled) use ($request) {
            $this->assertSame($handled, $request);
        });
    }

    public function testDomainMatchesAndSendsRequestThroughRouter()
    {
        $this->app[Domain::class] = new Validation\Domain('foo.bar');
        $this->app[Prefix::class] = new Validation\Prefix(null);
        $this->app[Accept::class] = new Validation\Accept(new AcceptParser('vnd', 'api', 'v1', 'json'));

        $request = IlluminateRequest::create('http://foo.bar/baz', 'GET');

        $this->router->shouldReceive('dispatch')->once();

        $this->middleware->handle($request, function () {
            //
        });

        $request = IlluminateRequest::create('http://bing.foo.bar/baz', 'GET');

        $this->middleware->handle($request, function ($handled) use ($request) {
            $this->assertSame($handled, $request);
        });
    }

    public function testTerminateMiddleware()
    {
        $request = Request::createFrom(
            IlluminateRequest::create('http://foo.bar/baz', 'GET')
        );
        $request->setRouteResolver(function () {
            return "/test/route";
        });
        $response             = new Response();
        $this->app['request'] = $request;
        $this->router->shouldReceive('gatherRouteMiddlewares')->once()->andReturn([]);
        $this->expectNotToPerformAssertions();
        $this->middleware->terminate($request, $response);
    }
}
