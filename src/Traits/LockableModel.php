<?php

namespace LowerRockLabs\Lockable\Traits;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use LowerRockLabs\Lockable\Models\ModelLock;

trait LockableModel
{
    private $acquiringLock = false;

    public static function bootLockable()
    {
        static::updating(function (Model $model) {
            // are we currently acquiring the lock
            if ($model->acquiringLock) {
                // if we are, we always want to allow the update
                $model->acquiringLock = false;

                return true;
            }

            if ($model->lockable()->count() > 0 && $model->lockable()->user()->id == Auth::id()) {
                return true;
            }

            if ($model->lockable()->expires_at < Carbon::now()) {
                $model->lockable()->delete();

                return true;
            }

            // throw an exception
            throw new Exception('User does not hold the lock to this model.');
            // stop the update
            return false;
        });
    }

    public function lockable()
    {
        return $this->morphTo(ModelLock::class, 'lockable');
    }

    public function isLocked()
    {
        if ($this->lockable()->expires_at < Carbon::now()) {
            $model->lockable()->delete();
        }

        return (bool) $this->lockable()->count();
    }

    /**
     * Acquire the lock for this model
     *
     * @return bool
     */
    public function acquireLock(): bool
    {
        // set the flag to make sure that locks can be acquired
        $this->acquiringLock = true;

        // set the column required to save the lock

        $this->lockable()->firstOrNew([
            'locked_by' => Auth::id(),
            'expires_at' => Carbon::now()->addSeconds(config('lockable.duration', '3600')), ]);

        return true;
    }

    /**
     * Release the lock for this model
     *
     * @return bool
     */
    public function releaseLock(): bool
    {
        // set the flag to make sure that locks can be released
        $this->acquiringLock = true;
        $this->lockable()->delete(); // locked_by = null;

        return true;
    }
}
