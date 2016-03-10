<?php

namespace App\Events;

use App\Events\Event;
use App\GrabbedLink;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LinkGrabbedEvent extends Event
{
    use SerializesModels;

    public $grabbedLink;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(GrabbedLink $grabbedLink)
    {
        $this->grabbedLink = $grabbedLink;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
