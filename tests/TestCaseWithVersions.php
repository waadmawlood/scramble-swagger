<?php

namespace Tests;

use Dedoc\Scramble\ScrambleServiceProvider;
use Waad\ScrambleSwagger\ScrambleSwaggerServiceProvider;
use Workbench\App\Http\Controllers\TestController;
use Workbench\App\Providers\WorkbenchServiceProvider;

abstract class TestCaseWithVersions extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            WorkbenchServiceProvider::class,
            ScrambleServiceProvider::class,
            ScrambleSwaggerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('scramble-swagger.versions', ['all', 'v1', 'v2']);
        $app['config']->set('scramble-swagger.default_version', 'v2');
    }

    public function defineRoutes($router): void
    {
        $router->get('api/v1/test', [TestController::class, 'index']);
        $router->get('api/v1/test/test', [TestController::class, 'index']);
        $router->get('api/v2/test', [TestController::class, 'index']);
        $router->get('api/v2/test/test', [TestController::class, 'index']);
    }
}
