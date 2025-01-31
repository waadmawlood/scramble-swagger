<?php

namespace Tests;

use Dedoc\Scramble\ScrambleServiceProvider;
use Waad\ScrambleSwagger\ScrambleSwaggerServiceProvider;
use Workbench\App\Http\Controllers\TestController;
use Workbench\App\Providers\WorkbenchServiceProvider;

abstract class TestCaseWithoutVersions extends \Orchestra\Testbench\TestCase
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
        // $app['config']->set('scramble-swagger', [
        //     'enable' => true,
        //     'url' => 'docs/swagger',
        //     'versions' => [
        //         'all',
        //     ],
        //     'default_version' => 'all',
        // ]);
    }

    public function defineRoutes($router): void
    {
        $router->get('api/test', [TestController::class, 'index']);
    }
}
