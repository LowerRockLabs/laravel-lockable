<?php

namespace LowerRockLabs\LaravelLivewireTablesDateRangeFilter;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelLivewireTablesDateRangeFilterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'lockable');
        // $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // $this->loadRoutesFrom(__DIR__ . '/../routes/lockable.php');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', '');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lrldaterangefilter');
        $this->registerRoutes();
        if ($this->app->runningInConsole()) {

            $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'lrldaterangefilter');

            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/lowerrocklabs/lrldaterangefilter'),
            ], 'lang');

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('lrldaterangefilter.php'),
            ], 'config');

        }
    }


    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'lrldaterangefilter');
    }
}
