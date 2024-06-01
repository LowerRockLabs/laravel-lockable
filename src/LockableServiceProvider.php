<?php

namespace LowerRockLabs\Lockable;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LowerRockLabs\Lockable\Commands\{FlushAll,FlushExpired};
use LowerRockLabs\Lockable\Models\ModelLock;

class LockableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'lockable');
        // $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // $this->loadRoutesFrom(__DIR__ . '/../routes/lockable.php');
        $this->app->booted(function () {
            if (config('laravel-lockable.scheduled_task_enable', true)) {
                $schedule = app(Schedule::class);
                $schedule->command('locks:flushexpired')->everyTenMinutes()->runInBackground();
            }
        });
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravellockable');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravellockable');
        $this->registerRoutes();
        if ($this->app->runningInConsole()) {
            if (! class_exists(\CreateModelLocksTable::class)) {
                $timestamp = date('Y_m_d_His');

                $this->publishes([
                    __DIR__.'/../database/migrations/create_model_locks_table.php' => database_path("/migrations/{$timestamp}_create_model_locks_table.php"),
                    __DIR__.'/../database/migrations/create_model_lock_watchers_table.php' => database_path("/migrations/{$timestamp}_create_model_lock_watchers_table.php"),

                ], 'laravel-lockable-migrations');

            }
            $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravellockable');

            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/lowerrocklabs'),
            ], 'laravel-lockable-lang');

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-lockable.php'),
            ], 'laravel-lockable-config');

            $this->commands([
                FlushAll::class,
                FlushExpired::class,
            ]);
        }
    }

    protected function registerRoutes()
    {
        if (config('laravel-lockable.publish_routes', true)) {
            Route::group($this->routeConfiguration(), function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            });
        }
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('laravel-lockable.prefix', 'lockable'),
            'middleware' => config('laravel-lockable.middleware', ['web']),
        ];
    }

    public function register()
    {
        // Register the main class to use with the facade
        $this->app->singleton('lockable', function () {
            return new ModelLock();
        });

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-lockable');
    }
}
