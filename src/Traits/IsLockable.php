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

    public $lockDuration;


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

            throw new Exception('User does not hold the lock to this model.');
        });

        static::updated(function (Model $model) {
            if ($model->lockable->user_id == Auth::id()) {
                $lockables = $model->lockable->first()->delete();
            }
        });
    }

    public function lockable()
    {
        return $this->morphOne(ModelLock::class, 'lockable');
    }

    public function isLocked()
    {
        if (! empty($this->lockable) && Carbon::now()->gte($this->lockable->expires_at)) {
            $this->releaseLock();

            return false;
        } elseif (! empty($this->lockable) && $this->lockable->user_id != Auth::id()) {
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
        if (! $this->isLocked()) {
            $this->lockDuration = (isset($this->modelLockDuration) ? $this->modelLockDuration : config('lockable.duration', '3600'));

            $lock = $this->lockable()->firstOrNew();
            $lock->user_id = Auth::id();

            $lock->expires_at = Carbon::now()->addSeconds($this->lockDuration);
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
        $lockables = $this->lockable->first()->delete();

        return true;
    }
}
