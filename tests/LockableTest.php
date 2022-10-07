<?php

namespace LowerRockLabs\Lockable\Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use LowerRockLabs\Lockable\Events\ModelWasLocked;
use LowerRockLabs\Lockable\Events\ModelWasUnlocked;
use LowerRockLabs\Lockable\Tests\Models\Note;
use LowerRockLabs\Lockable\Tests\Models\User;
use LowerRockLabs\Lockable\Tests\Models\Admin;

class LockableTest extends TestCase
{
    /** @test */
    public function migrationsContainsModelLocksTable()
    {
        $this->assertTrue(Schema::hasColumn('model_locks', 'user_id'));
    }

    /** @test */
    public function migrationsContainsModelLockWatcheresTable()
    {
        $this->assertTrue(Schema::hasColumn('model_lock_watchers', 'model_lock_id'));
    }

    /** @test */
    public function migrationsContainsNotesTable()
    {
        $this->assertTrue(Schema::hasColumn('notes', 'title'));
    }

    /** @test */
    public function migrationsContainsUsersTable()
    {
        $this->assertTrue(Schema::hasColumn('users', 'name'));
    }

    /** @test */
    public function migrationsContainsAdminsTable()
    {
        $this->assertTrue(Schema::hasColumn('admins', 'name'));
    }

    /** @test */
    public function canCreateAUser()
    {
        // given a user
        $user = factory(User::class)->create();
        $user->update(['name' => 'Test User 1']);
        $user->save();

        Auth::login($user);

        $this->assertEquals('Test User 1', $user->name);
    }

    /** @test */
    public function canCreateAnAdmin()
    {
        // given a user
        $admin = factory(Admin::class)->create();
        $admin->update(['name' => 'Test Admin 1']);
        $admin->save();

        Auth::login($admin);

        $this->assertEquals('Test Admin 1', $admin->name);
    }


    /** @test */
    public function canCreateANoteAndObtainLock()
    {
        $user2 = factory(User::class)->create();
        $user2->update(['name' => 'Test User 2']);
        $user2->save();
        Auth::login($user2);

        $note = factory(Note::class)->create();

        $lock = $note->lockable()->firstOrNew();
        $lock->user_id = Auth::id();
        $lock->expires_at = Carbon::now()->addSeconds('3600');
        $lock->save();

        $note->update(['title' => 'Test Note 1']);
        $note->save();

        $this->assertEquals('Test Note 1', $note->title);
    }

    /** @test */
    public function canCreateANoteAndObtainLockAsAdmin()
    {
        $admin = factory(Admin::class)->create();
        $admin->update(['name' => 'Test Admin 2']);
        $admin->save();
        Auth::login($admin);

        $note = factory(Note::class)->create();

        $lock = $note->lockable()->firstOrNew();
        $lock->user_id = Auth::id();
        $lock->user_type = get_class($admin);
        $lock->expires_at = Carbon::now()->addSeconds('3600');
        $lock->save();
        $note->update(['title' => 'Test Note 1']);
        $note->save();

        $this->assertEquals('Test Note 1', $note->title);
        $this->assertEquals($lock->user_type, get_class($admin));

    }

    /** @test */
    public function testNotLockedForSameUser()
    {
        $user1 = factory(User::class)->create();
        Auth::login($user1);

        $note = factory(Note::class)->create();
        $note->acquireLock();

        $this->assertFalse($note->isLocked());
    }

    /** @test */
    public function testIsLockedForAnotherUser()
    {
        $user1 = factory(User::class)->create();
        Auth::login($user1);

        $note = factory(Note::class)->create();
        $note->acquireLock();

        $user2 = factory(User::class)->create();
        Auth::login($user2);

        $this->assertTrue($note->isLocked());
    }

    /** @test */
    public function testCanAccessModelWithoutLock()
    {
        $user1 = factory(User::class)->create();
        Auth::login($user1);

        $note = factory(Note::class)->create(['title' => 'Test Note No Events']);

        $user2 = factory(User::class)->create();
        Auth::login($user2);
        $note->acquireLock();
        $note->update(['title' => 'Test Note 9']);
        $note->save();

        $this->assertEquals('Test Note 9', $note->title);
    }

    /** @test */
    public function testLockRemovalAfterExpiryAllowsAccess()
    {
        $user1 = factory(User::class)->create();
        Auth::login($user1);

        $note = factory(Note::class)->create();
        $lock = $note->lockable()->firstOrNew();
        $lock->user_id = Auth::id();
        $lock->expires_at = Carbon::now()->subSeconds('3600');
        $lock->save();

        $user2 = factory(User::class)->create();
        Auth::login($user2);

        $this->assertFalse($note->isLocked());
    }

    /** @test */
    public function testLockedModelReturnsFalseWhenUpdating()
    {
        $this->expectExceptionMessage('User does not hold the lock to this model.');
        $user3 = factory(User::class)->create();
        $user3->update(['name' => 'Test User 2']);
        $user3->save();
        Auth::login($user3);

        $note = factory(Note::class)->create();
        $note->acquireLock();

        $note->update(['title' => 'Test Note 1']);
        $note->save();

        $user4 = factory(User::class)->create();
        $user4->update(['name' => 'Test User 3']);
        $user4->save();

        Auth::login($user4);
        $note->update(['title' => 'Test Note 4']);
        $note->save();
    }

    /** @test */
    public function testLockDurationIsConfigurablePerModel()
    {
        $user1 = factory(User::class)->create();
        Auth::login($user1);

        $note = factory(Note::class)->create();
        $note->modelLockDuration = '8000';
        $note->acquireLock();
        $this->assertTrue(Carbon::now()->addSeconds('4000')->lte($note->lockable->expires_at));
    }

    /** @test */
    public function testEventModelWasLocked()
    {
        Event::fake();

        $user1 = factory(User::class)->create();
        Auth::login($user1);

        $note = factory(Note::class)->create();
        $note->acquireLock();
        Event::assertDispatched(ModelWasLocked::class);
    }

    /** @test */
    public function testEventModelWasUnlocked()
    {
        Event::fake();

        $user1 = factory(User::class)->create();
        Auth::login($user1);

        $note = factory(Note::class)->create();
        $note->acquireLock();
        $note->releaseLock();
        Event::assertDispatched(ModelWasUnlocked::class);
    }

    /** @test */
    public function testFlushExpiredLocks()
    {
        $this->artisan('locks:flushexpired')->assertExitCode(0);
    }

    /** @test */
    public function testFlushAllLocks()
    {
        $this->artisan('locks:flushall')->assertExitCode(0);
    }
}
