<?php

namespace Dingo\Api\Tests\Stubs;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;

class Application9Stub extends Container implements Application
{
    public function version()
    {
        return 'v1';
    }

    public function basePath($path = '')
    {
        //
    }

    public function bootstrapPath($path = '')
    {
        //
    }

    public function configPath($path = '')
    {
        //
    }

    public function databasePath($path = '')
    {
        //
    }

    public function resourcePath($path = '')
    {
        //
    }

    public function storagePath($path = '')
    {
        //
    }

    public function environment(...$environments)
    {
        return 'testing';
    }

    public function runningInConsole()
    {
        //
    }

    public function runningUnitTests()
    {
        //
    }

    public function maintenanceMode()
    {
//        return new class implements MaintenanceMode {
//            public function activate(array $payload): void
//            {
//                // TODO: Implement activate() method.
//            }
//
//            public function deactivate(): void
//            {
//                // TODO: Implement deactivate() method.
//            }
//
//            public function active(): bool
//            {
//                return false;
//            }
//
//            public function data(): array
//            {
//                return [];
//            }
//        };
    }

    public function isDownForMaintenance()
    {
        return false;
    }

    public function registerConfiguredProviders()
    {
        //
    }

    public function register($provider, $options = [], $force = false)
    {
        //
    }

    public function registerDeferredProvider($provider, $service = null)
    {
        //
    }

    public function resolveProvider($provider)
    {
        //
    }

    public function boot()
    {
        //
    }

    public function booting($callback)
    {
        //
    }

    public function booted($callback)
    {
        //
    }

    public function bootstrapWith(array $bootstrappers)
    {
        //
    }

    public function getLocale()
    {
        //
    }

    public function getNamespace()
    {
        //
    }

    public function getProviders($provider)
    {
        //
    }

    public function hasBeenBootstrapped()
    {
        //
    }

    public function loadDeferredProviders()
    {
        //
    }

    public function setLocale($locale)
    {
        //
    }

    public function shouldSkipMiddleware()
    {
        //
    }

    public function terminating($callback)
    {
        //
    }

    public function terminate()
    {
        //
    }
}
