<?php

namespace LowerRockLabs\Lockable\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use LowerRockLabs\Lockable\Models\ModelLock;

class ModelWasUnlocked implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $modellock;

    /**
     * Create a new event instance.
     */
    public function __construct(ModelLock $modellock)
    {
        $this->modellock = $modellock;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        $channels = [];
        if (! empty($this->modellock->lockWatchers)) {
            foreach ($this->modellock->lockWatchers as $lockWatcher) {
                $channels[] = new PrivateChannel(str_replace('\\', '.', $lockWatcher->user_type).'.'.$lockWatcher->user_id);
            }
        }

        return $channels;
    }

    /**
     * Get what the event should be broadcasted as
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'ModelWasUnlocked';
    }

    /**
     * Get what the event should be broadcasted with
     *
     * @return array
     */
    public function broadcastWith()
    {
        return ['modeltype' => get_class($this->modellock->lockable), 'modelid' => $this->modellock->lockable->id];
    }
}
