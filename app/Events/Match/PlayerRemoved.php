<?php

namespace App\Events\Match;

use App\Models\Match;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PlayerRemoved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
	public $user;
	public $match;
	public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
	public function __construct(User $user, Match $match, $message)
	{
		$this->user = $user;
		$this->match = $match;
		$this->message = $message;
	}


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
