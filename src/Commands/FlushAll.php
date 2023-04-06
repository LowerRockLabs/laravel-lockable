<?php

namespace LowerRockLabs\Lockable\Commands;

use Illuminate\Console\Command;
use LowerRockLabs\Lockable\Models\ModelLock;

class FlushAll extends Command
{
    public $signature = 'locks:flushall';

    public $description = 'Flush all locks';

    public function handle(): int
    {
        $locksToBeUnlocked = ModelLock::get();
        foreach ($locksToBeUnlocked as $lockToBeUnlocked) {
            $lockToBeUnlocked->delete();
        }

        $this->comment('All done');

        return self::SUCCESS;
    }
}
