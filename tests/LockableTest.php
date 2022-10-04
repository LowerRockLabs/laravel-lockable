<?php

namespace LowerRockLabs\Lockable\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use TiMacDonald\Log\LogFake;

class LockableTest extends TestCase
{
    use WithFaker;

    private $user;
    private $otherUser;

    public function setUp(): void
    {
        parent::setUp();
        Config::set('mail.default', 'array');

        $this->user = new User(['id' => $this->faker->numberBetween(1, 10000), 'name' => $this->faker->name, 'email' => $this->faker->email]);
        $this->otherUser = new User(['id' => $this->faker->numberBetween(1, 10000), 'name' => $this->faker->name, 'email' => $this->faker->email]);
    }

    /** @test */
    public function locksCanBeReleasedOnAModel()
    {
        // given a user
        $user = factory(User::class)->create();
        Auth::login($user);

        // and an article
        $note = factory(Note::class)->create();
        /** @var Note $note */

        // a lock can be acquired
        $note->acquireLock();

        // a lock can then be released
        $note = $note->fresh();
        $note->releaseLock();

        // the database has the correct lock details
        $note = $note->fresh();

        $this->assertNull($note->lockable->user_id);
    }

    /** @test */
    /*public function it_will_skip_notifications_until_limit_expires()
    {
        Event::fake();
        Notification::fake();

        $this->app->singleton(ChannelManager::class, function ($app) {
            return new RateLimitChannelManager($app);
        });
        // Ensure we are starting clean
        Log::swap(new LogFake);
        Log::assertNotLogged('notice');
        // Send first notification and expect it to succeed
        $this->user->notify(new TestNotification());
        Event::assertDispatched(NotificationSent::class);
        Event::assertNotDispatched(NotificationRateLimitReached::class);
        // Send second notification and expect it to be skipped
        Log::assertNotLogged('notice');
        $this->user->notify(new TestNotification());
        Event::assertDispatched(NotificationRateLimitReached::class);
        Log::assertLogged('notice');
    }*/

    /** @test */
    /*public function it_does_not_get_confused_between_multiple_users()
    {
        Event::fake();
        Notification::fake();

        $this->app->singleton(ChannelManager::class, function ($app) {
            return new RateLimitChannelManager($app);
        });
        Config::set('laravel-notification-rate-limit.rate_limit_seconds', 10);

        // Ensure we are starting clean
        Log::swap(new LogFake);
        Log::assertNotLogged('notice');
        // Send first notification and expect it to succeed
        $this->user->notify(new TestNotification());
        Event::assertDispatched(NotificationSent::class);
        Event::assertNotDispatched(NotificationRateLimitReached::class);
        // Send a notification to another user and expect it to succeed
        $this->otherUser->notify(new TestNotification());
        Event::assertDispatched(NotificationSent::class);
        Event::assertNotDispatched(NotificationRateLimitReached::class);
        // Send a second notice to the first user and expect it to be skipped
        Log::assertNotLogged('notice');
        $this->user->notify(new TestNotification());
        Event::assertDispatched(NotificationRateLimitReached::class);
        Log::assertLogged('notice');
    }*/

    /** @test */
    /*
    public function it_will_resume_notifications_after_expiration()
    {
        Event::fake();
        Notification::fake();

        Config::set('laravel-notification-rate-limit.rate_limit_seconds', 0.1);

        $this->app->singleton(ChannelManager::class, function ($app) {
            return new RateLimitChannelManager($app);
        });
        // Ensure we are starting clean.
        Log::swap(new LogFake);
        Log::assertNotLogged('notice');
        // Send first notification and expect it to succeed.
        $this->user->notify(new TestNotification());
        Event::assertDispatched(NotificationSent::class);
        Event::assertNotDispatched(NotificationRateLimitReached::class);
        Log::assertNotLogged('notice');
        // Wait until the rate limiter has expired
        sleep(0.1);
        // Send another notification and expect it to succeed.
        Event::assertDispatched(NotificationSent::class);
        Event::assertNotDispatched(NotificationRateLimitReached::class);
        Log::assertNotLogged('notice');
    }*/
}
