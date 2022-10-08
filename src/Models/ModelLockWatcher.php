<?php

namespace LowerRockLabs\Lockable\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * LowerRockLabs\Lockable\Models\ModelLockWatcher
 *
 * @property int $id
 * @property int $model_lock_id
 * @property string $user_id
 * @property string $user_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \LowerRockLabs\Lockable\Models\ModelLock $modelLock
 * @property-read Model|\Eloquent $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLockWatcher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLockWatcher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLockWatcher query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLockWatcher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLockWatcher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLockWatcher whereModelLockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLockWatcher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLockWatcher whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelLockWatcher whereUserType($value)
 * @mixin \Eloquent
 */
class ModelLockWatcher extends Model
{
    protected $table = 'model_lock_watchers';

    protected $guarded = ['model_lock_id', 'user_id', 'user_type'];

    public function __construct()
    {
        $this->table = config('laravel-lockable.lock_watchers_table', 'model_lock_watchers');
    }

    /**
     * Watchable model relation!
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modelLock()
    {
        return $this->belongsTo(ModelLock::class);
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
}
