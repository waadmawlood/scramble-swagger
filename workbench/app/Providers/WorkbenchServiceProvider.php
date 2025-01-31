<?php

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;

class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        app()->usePublicPath(__DIR__.'/../public');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
