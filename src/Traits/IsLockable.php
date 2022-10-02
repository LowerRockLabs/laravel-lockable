<?php

namespace LowerRockLabs\Lockable\Traits;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use LowerRockLabs\Lockable\Models\ModelLock;

trait IsLockable
{
    private $acquiringLock = false;

    public $modelLockable = true;

    public static function bootIsLockable()
    {
        static::updating(function (Model $model) {
            // are we currently acquiring the lock
            if ($model->acquiringLock) {
                // if we are, we always want to allow the update
                $model->acquiringLock = false;

                return true;
            }

            if (! empty($model->lockable) && $model->lockable->user_id == Auth::id()) {
                return true;
            }

            // throw an exception
            throw new Exception('User does not hold the lock to this model.');
            // stop the update
            return false;
        });

        static::updated(function (Model $model) {
            return static::withoutEvents(function () use ($model) {
                $model->lockable()->delete();
            });
        });
    }

    public function lockable()
    {
        return $this->morphOne(ModelLock::class, 'lockable');
    }

    public function isLocked()
    {
        if (! empty($this->lockable) > 0 && $this->lockable->expires_at < Carbon::now()) {
            return static::withoutEvents(function () use ($this) {
                $this->lockable()->delete();
                return false;
            });
        } elseif (! empty($this->lockable) > 0 && $this->lockable->user_id != Auth::id()) {
            return true;
        } else {
            return false;
        }
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
        if (!$this->isLocked()) {
            $lock = $this->lockable()->firstOrNew();
            $lock->user_id = Auth::id();
            $lock->expires_at = Carbon::now()->addSeconds(config('lockable.duration', '3600'));
            $lock->save();

            return true;
        }
        return false;
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
        return static::withoutEvents(function () use ($this) {
            $this->lockable()->delete();
            return true;
        });
    }
}
