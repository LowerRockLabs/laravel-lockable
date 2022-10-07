<?php

namespace LowerRockLabs\Lockable\Traits;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use LowerRockLabs\Lockable\Events\ModelUnlockRequested;
use LowerRockLabs\Lockable\Models\ModelLock;

trait IsLockable
{
    private $acquiringLock = false;

    public $modelLockable = true;

    public $lockDuration;

    public static function bootIsLockable()
    {
        if (config('laravel-lockable.get_locked_on_retrieve', true)) {
            static::retrieved(function (Model $model) {
                if (! empty($model->lockable)) {
                    $model->lockHolderName = $model->lockable->user->name;
                }
            });
        }

        if (config('laravel-lockable.prevent_updating', true)) {
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
        }

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
            $this->acquireLock();

            return false;
        } elseif (! empty($this->lockable) && $this->lockable->user_id != Auth::id()) {
            return true;
        } elseif (! empty($this->lockable) && $this->lockable->user_id == Auth::id()) {
            return false;
        } else {
            $this->acquireLock();

            return false;
        }
    }

    /**
     * Acquire the lock for this model
     */
    public function acquireLock()
    {
        // set the flag to make sure that locks can be acquired

        if (! $this->acquiringLock) {
            $this->acquiringLock = true;
        }

        if (! isset($this->lockDuration)) {
            $this->lockDuration = (isset($this->modelLockDuration) ? $this->modelLockDuration : config('lockable.duration', '3600'));
        }

        $lock = $this->lockable()->firstOrNew();
        $lock->user_id = Auth::id();

        $lock->expires_at = Carbon::now()->addSeconds($this->lockDuration);
        $lock->save();

        return true;
    }

    /**
     * Release the lock for this model
     */
    public function releaseLock()
    {
        // set the flag to make sure that locks can be released

        if (! $this->acquiringLock) {
            $this->acquiringLock = true;
        }
        $lockables = $this->lockable->first()->delete();

        return true;
    }

    /**
     * Request the lock for this model
     */
    public function requestLock($user)
    {
        $this->user = Auth::user();

        $authModel = get_class($this->user);
        $authID = $this->user->id;
        if ($this->lockable->lockWatchers()->where('user_type', $authModel)->where('user_id', $authID)->count() < 1) {
            $newLockWatcher = $this->lockable->lockWatchers()->create();
            $newLockWatcher->user_id = $authID;
            $newLockWatcher->user_type = $authModel;
            $newLockWatcher->save();
        }
        ModelUnlockRequested::dispatch($this->lockable, $user);

        return true;
    }
}
