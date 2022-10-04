<?php

namespace LowerRockLabs\Lockable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use LowerRockLabs\Lockable\Traits\IsLockable;

class Note extends Model
{
    use IsLockable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
    ];
}
