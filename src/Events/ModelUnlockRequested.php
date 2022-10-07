<?php

namespace LowerRockLabs\Lockable\Events;

use App\Models\User;
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

    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(ModelLock $modellock, User $user)
    {
        $this->modellock = $modellock;
        $this->user = $user;
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
        return ['id' => $this->user->id, 'modeltype' => get_class($this->modellock->lockable), 'modelid' => $this->modellock->lockable->id];
    }
}
