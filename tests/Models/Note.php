<?php

namespace LowerRockLabs\Lockable\Tests\Models;

use LowerRockLabs\Lockable\Traits\IsLockable;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use IsLockable;

    public $modelLockDuration = "3600";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body'
    ];
}
