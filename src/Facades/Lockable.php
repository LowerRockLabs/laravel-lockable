<?php

namespace LowerRockLabs\Lockable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LowerRockLabs\Lockable\Lockable
 */
class Lockable extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \LowerRockLabs\Lockable\Lockable::class;
    }
}
