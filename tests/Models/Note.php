<?php

namespace LowerRockLabs\Lockable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use LowerRockLabs\Lockable\Traits\IsLockable;

class Note extends Model
{
    use IsLockable;

    public $modelLockDuration = '3600';

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
