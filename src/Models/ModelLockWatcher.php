<?php

namespace LowerRockLabs\Lockable\Models;

use Illuminate\Database\Eloquent\Model;

class ModelLockWatcher extends Model
{
    protected $table = 'model_lock_watchers';

    public function __construct()
    {
        $this->table = config('laravel-lockable.lock_watchers_table', 'model_lock_watchers');
    }

    /**
     * Watchable model relation.
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
