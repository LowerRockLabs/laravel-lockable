<?php

namespace LowerRockLabs\Lockable\Models;

use Illuminate\Database\Eloquent\Model;
use LowerRockLabs\Lockable\Events\ModelWasLocked;
use LowerRockLabs\Lockable\Events\ModelWasUnLocked;

class ModelLock extends Model
{
    protected $table = 'model_locks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'expires_at',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $events = [
        'saved' => ModelWasLocked::class,
        'deleted' => ModelWasUnLocked::class,
    ];

    /**
     * Watchable model relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function lockable()
    {
        return $this->morphTo();
    }

    /**
     * User model relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
