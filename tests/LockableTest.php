<?php

namespace LowerRockLabs\Lockable\Tests;

//use LowerRockLabs\Lockable\Tests\Models\Article;
use LowerRockLabs\Lockable\Tests\Models\User;
use LowerRockLabs\Lockable\Tests\Models\Note;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class LockableTest extends TestCase
{
    /** @test */
    public function migrationsContainsModelLocksTable()
    {
        $this->assertTrue(Schema::hasColumn('model_locks', 'user_id'));
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
    public function cannotUpdateALockedModel()
    {
        $this->expectExceptionMessage('User does not hold the lock to this model.');
        $user3 = factory(User::class)->create();
        $user3->update(['name' => 'Test User 2']);
        $user3->save();
        Auth::login($user3);

        $note = factory(Note::class)->create();

        $lock = $note->lockable()->firstOrNew();
        $lock->user_id = Auth::id();
        $lock->expires_at = Carbon::now()->addSeconds('3600');
        $lock->save();

        $note->update(['title' => 'Test Note 1']);
        $note->save();



        $user4 = factory(User::class)->create();
        $user4->update(['name' => 'Test User 3']);
        $user4->save();

        Auth::login($user4);
        $note->update(['title' => 'Test Note 4']);
        $note->save();
    }
}
