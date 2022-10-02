<?php

namespace LowerRockLabs\Lockable\Commands;

use Illuminate\Console\Command;

class LockableCommand extends Command
{
    public $signature = 'laravel-lockable';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
