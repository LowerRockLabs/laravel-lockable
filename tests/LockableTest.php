<?php

namespace LowerRockLabs\Lockable\Tests;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use LowerRockLabs\Lockable\Tests\Models\Note;
use LowerRockLabs\Lockable\Tests\Models\User;

class LockableTest extends TestCase
{
    public function migrationsCanContainLocksTable()
    {
        // when lockable is created on a migration

        // the table contains a user_id column
        $this->assertTrue(Schema::hasColumn('model_locks', 'user_id'));
    }

    /** @test */
    public function modelsCanNotBeUpdatedIfTheUserDoesntOwnTheLock()
    {
        // theres going to be an exception
        $this->expectException(Exception::class);

        // given a user
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        Auth::login($user1);

        // and an note
        $note = factory(Note::class)->create();
        /** @var Note $note */
        $note->acquireLock();

        Auth::login($user2);
        // when it is updated
        $note->update(['title' => 'This is an updated title']);
        $note->save();

        // the database does not update
        $note = $note->fresh();

        $this->assertNotEquals('This is an updated title', $note->title);
    }

    /** @test */
    public function locksCanBeAcquiredOnAModel()
    {
        // given a user
        $user = factory(User::class)->create();
        Auth::login($user);

        // and an article
        $note = factory(Note::class)->create();
        /** @var Note $note */

        // a lock can be acquired
        $note->acquireLock();

        // the database has the correct lock details
        $note = $note->fresh();

        $this->assertEquals($user->id, $note->lockable->user_id);
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
    public function ifTheUserHoldsTheLockTheyCanUpdate()
    {
        // given a user
        $user = factory(User::class)->create();
        Auth::login($user);

        // and an article
        $article = factory(Article::class)->create();
        /** @var Article $article */

        // the user locks the article
        $article->acquireLock();

        // a lock can then be released
        $article = $article->fresh();

        // when it is updated
        $article->update(['title' => 'This is an updated title']);
        $article->save();

        // the database does not update
        $article = $article->fresh();

        $this->assertEquals('This is an updated title', $article->title);
    }
}
