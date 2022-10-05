<?php

namespace LowerRockLabs\Lockable\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use LowerRockLabs\Lockable\LockableServiceProvider;
use LowerRockLabs\Lockable\Tests\Models\User;

//use Orchestra\Testbench\TestCase as Orchestra;

//class TestCase extends Orchestra
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
    }


    /**
 * Define database migrations.
 *
 * @return void
 */
    protected function defineDatabaseMigrations()
    {
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadLaravelMigrations(['--database' => 'testbench']);
    }


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
        'driver'   => 'sqlite',
        'database' => ':memory:',
        'prefix'   => '',
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
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');



        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-lockable_table.php.stub';
        $migration->up();sff
        */
    }
}
