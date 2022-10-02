<?php

namespace LowerRockLabs\Lockable;

class Lockable
{
    private $app;

    public function __construct()
    {
        $this->app = app();
    }
}
