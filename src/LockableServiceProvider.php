<?php

namespace LowerRockLabs\Lockable;

use LowerRockLabs\Lockable\Commands\LockableCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LockableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-lockable')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_model_locks_table')
            ->hasCommand(LockableCommand::class);
    }

    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'lockable');
        // $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lockable');
        // $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // $this->loadRoutesFrom(__DIR__ . '/../routes/lockable.php');

        if ($this->app->runningInConsole()) {
            if (! class_exists(\CreateModelLocksTable::class)) {
                $timestamp = date('Y_m_d_His');

                $this->publishes([
                    __DIR__ . '/../database/migrations/create_model_locks_table.php' => database_path("/migrations/{$timestamp}_create_model_locks_table.php"),
                ], 'migrations');
            }

            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('lockable.php'),
            ], 'config');

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

        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'lockable');
    }
}
