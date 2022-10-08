<?php

namespace LowerRockLabs\Lockable\Models;

use Illuminate\Database\Eloquent\Model;
use LowerRockLabs\Lockable\Events\ModelWasLocked;
use LowerRockLabs\Lockable\Events\ModelWasUnlocked;

class ModelLock extends Model
{
    protected $table = 'model_locks';

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => ModelWasLocked::class,
        'deleting' => ModelWasUnlocked::class,
    ];

    protected $events = [
        'created' => ModelWasLocked::class,
        'deleting' => ModelWasUnlocked::class,
    ];

    public function __construct()
    {
        $this->table = config('laravel-lockable.locks_table');
    }

    /**
     * Watchable model relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function lockable()
    {
        return $this->morphTo();
    }

    public function lockWatchers()
    {
        return $this->hasMany(ModelLockWatcher::class);
    }

    public function lockWatcherUsers()
    {
        return $this->hasManyThrough(config('auth.providers.users.model'), ModelLockWatcher::class, 'model_lock_id', 'id', 'id', 'user_id');
    }

    /**
     * User model relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        //return $this->belongsTo(config('auth.providers.users.model'));
        return $this->morphTo();
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }
}
