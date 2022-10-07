<?php

namespace LowerRockLabs\Lockable;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use LowerRockLabs\Lockable\Commands\FlushAll;
use LowerRockLabs\Lockable\Commands\FlushExpired;
use LowerRockLabs\Lockable\Models\ModelLock;

class LockableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'lockable');
        // $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lockable');
        // $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // $this->loadRoutesFrom(__DIR__ . '/../routes/lockable.php');
        $this->app->booted(function () {
            if (config('laravel-lockable.scheduled_task_enable', true)) {
                $schedule = app(Schedule::class);
                $schedule->command('locks:flushexpired')->everyTenMinutes()->runInBackground();
            }
        });

        if ($this->app->runningInConsole()) {
            if (! class_exists(\CreateModelLocksTable::class)) {
                $timestamp = date('Y_m_d_His');

                $this->publishes([
                    __DIR__.'/../database/migrations/create_model_locks_table.php' => database_path("/migrations/{$timestamp}_create_model_locks_table.php"),
                ], 'migrations');

                $this->publishes([
                    __DIR__.'/../database/migrations/create_model_lock_watchers_table.php' => database_path("/migrations/{$timestamp}_create_model_lock_watchers_table.php"),
                ], 'migrations');
            }
            $this->loadTranslationsFrom(__DIR__.'/../lang', 'laravel-lockable');

            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/lowerrocklabs'),
            ], 'lang');

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-lockable.php'),
            ], 'config');

            $this->commands([
                FlushAll::class,
                FlushExpired::class,
            ]);
            // Publishing the views.
            /*$this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/lockable'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__ . '/../resources/assets' => public_path('vendor/lockable'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__ . '/../resources/lang' => resource_path('lang/vendor/lockable'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
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
