<?php

namespace LowerRockLabs\Lockable\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use LowerRockLabs\Lockable\Tests\Models\User;
use LowerRockLabs\Lockable\LockableServiceProvider;

//use Orchestra\Testbench\TestCase as Orchestra;

//class TestCase extends Orchestra
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate', ['--database' => 'testbench']);
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
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
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Setup the right User class (using stub)
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('auth.providers.users', [
            'driver' => 'eloquent',
            'model' => User::class,
        ]);
        $app['config']->set('auth.guards.users', [
            'driver' => 'session',
            'provider' => 'admins',
        ]);

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-lockable_table.php.stub';
        $migration->up();
        */
    }
}
