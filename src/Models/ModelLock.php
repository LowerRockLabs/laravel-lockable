<?php

namespace LowerRockLabs\Lockable\Models;

use Illuminate\Database\Eloquent\Model;
use LowerRockLabs\Lockable\Events\{ModelWasLocked,ModelWasUnlocked};
use Illuminate\Database\Eloquent\Relations\{BelongsTo,HasMany,HasManyThrough,MorphTo};

/**
 * LowerRockLabs\Lockable\Models\ModelLock
 *
 * @property int $id
 * @property string $lockable_type
 * @property string $lockable_id
 * @property string $user_id
 * @property string|null $user_type
 * @property string $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $lockable
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLock expired()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLock query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLock whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLock whereLockableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLock whereLockableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLock whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLock whereUserType($value)
 * @mixin \Eloquent
 */
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
        'deleted' => ModelWasUnlocked::class,
    ];

    protected $events = [
        'created' => ModelWasLocked::class,
        'deleted' => ModelWasUnlocked::class,
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
    public function lockable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Watchers model relation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lockWatchers(): HasMany
    {
        return $this->hasMany(ModelLockWatcher::class);
    }

    /**
     * Watcher Users model relation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function lockWatcherUsers(): HasManyThrough
    {
        return $this->hasManyThrough(config('auth.providers.users.model'), ModelLockWatcher::class, 'model_lock_id', 'id', 'id', 'user_id');
    }

    /**
     * User model relation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }
}
