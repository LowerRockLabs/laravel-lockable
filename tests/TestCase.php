<?php

namespace LowerRockLabs\Lockable\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use LowerRockLabs\Lockable\LockableServiceProvider;

//use Orchestra\Testbench\TestCase as Orchestra;

//class TestCase extends Orchestra
class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        // Set Factories
        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'LowerRockLabs\\Lockable\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LockableServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-lockable_table.php.stub';
        $migration->up();
        */
    }
}
