<?php

namespace LowerRockLabs\Lockable\Commands;

use Illuminate\Console\Command;
use LowerRockLabs\Lockable\Models\ModelLock;

class FlushExpired extends Command
{
    public $signature = 'locks:flushexpired';

    public $description = 'Flush all expired locks';

    public function handle(): int
    {
        $locksToBeUnlocked = ModelLock::expired()->get();
        foreach ($locksToBeUnlocked as $lockToBeUnlocked)
        {
            $lockToBeUnlocked->delete();
        }

        $this->comment('All done');
        return self::SUCCESS;
    }
}
