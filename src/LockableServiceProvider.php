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
            ->hasMigration('create_laravel-lockable_table')
            ->hasCommand(LockableCommand::class);
    }
}
