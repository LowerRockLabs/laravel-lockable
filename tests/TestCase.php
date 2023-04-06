<?php

namespace LowerRockLabs\Lockable\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use LowerRockLabs\Lockable\LockableServiceProvider;
use LowerRockLabs\Lockable\Tests\Models\Admin;
use LowerRockLabs\Lockable\Tests\Models\User;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/database/factories');
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->artisan('migrate', ['--database' => 'testbench'])->run();

        $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback', ['--database' => 'testbench'])->run();
        });
    }

    /**
     * @param  mixed  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LockableServiceProvider::class,
        ];
    }

    /**
     * Ignore package discovery from.
     *
     * @return array<int, array>
     */
    public function ignorePackageDiscoveriesFrom()
    {
        return [];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Setup tfhe right User class (using stub)
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('auth.providers.users', [
            'driver' => 'eloquent',
            'model' => User::class,
        ]);
        $app['config']->set('auth.guards.users', [
            'driver' => 'session',
            'provider' => 'admins',
        ]);

        $app['config']->set('auth.providers.admins.model', Admin::class);
        $app['config']->set('auth.providers.admins', [
            'driver' => 'eloquent',
            'model' => Admin::class,
        ]);
        $app['config']->set('auth.guards.admins', [
            'driver' => 'session',
            'provider' => 'admins',
        ]);
    }

    public function getEnvironmentSetUp($app)
    {
        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-lockable_table.php.stub';
        $migration->up();sff
        */
    }
}
