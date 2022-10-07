<?php

namespace LowerRockLabs\Lockable\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use LowerRockLabs\Lockable\Models\ModelLock;

class ModelUnlockRequested implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $modellock;

    public $user_id;

    /**
     * Create a new event instance.
     */
    public function __construct(ModelLock $modellock, $user_id)
    {
        $this->modellock = $modellock;
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.'.$this->modellock->user_id);
    }

    public function broadcastAs()
    {
        return 'ModelUnlockRequested';
    }

    public function broadcastWith()
    {
        return ['id' => $this->user_id, 'modeltype' => get_class($this->modellock->lockable), 'modelid' => $this->modellock->lockable->id];
    }
}
