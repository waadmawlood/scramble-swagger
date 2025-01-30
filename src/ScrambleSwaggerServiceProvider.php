<?php

namespace Waad\ScrambleSwagger;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Waad\ScrambleSwagger\Commands\GenerateScrambleSwagger;

class ScrambleSwaggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/scramble-swagger.php', 'scramble-swagger');
        $this->commands([
            GenerateScrambleSwagger::class,
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/scramble-swagger.php' => config_path('scramble-swagger.php'),
            ], 'scramble-swagger-config');

            $this->publishes([
                __DIR__.'/scramble-swagger' => public_path('scramble-swagger'),
            ], 'scramble-swagger-assets');

            Artisan::call('vendor:publish', [
                '--provider' => "Dedoc\Scramble\ScrambleServiceProvider",
                '--tag' => 'scramble-config',
            ]);
        }

        $this->loadViewsFrom(__DIR__.'/views', 'scramble-swagger');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }
}
