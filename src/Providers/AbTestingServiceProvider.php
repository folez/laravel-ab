<?php

namespace folez\LaravelAB\Providers;

use folez\LaravelAB\AbTesting;
use folez\LaravelAB\Console\Commands\AbReport;
use folez\LaravelAB\Console\Commands\AbReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AbTestingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-ab.php' => config_path('laravel-ab.php'),
            ], 'config');

            $this->commands([
                AbReport::class,
                AbReset::class,
            ]);
        }

        Request::macro('abVariant', function () {
            return app(AbTesting::class)->getVariant();
        });

        Blade::if('ab', function ($variant) {
            return app(AbTesting::class)->isVariant($variant);
        });
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-ab.php', 'laravel-ab');

        // Register the main class to use with the facade
        $this->app->singleton('ab-testing', function () {
            return new AbTesting();
        });
    }
}
