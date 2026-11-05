<?php

namespace App\Events;

use App\Models\Schedules;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Nette\Utils\Arrays;

class SectionManagerEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public array $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $schedule)
    {
        $this->data = $schedule;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('schedule.user.' . $this->data['userId']);
    }
}
