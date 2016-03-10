<?php

namespace App\Listeners;

use App\Events\LinkGrabbedEvent;
use App\Jobs\GrabOffer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LinkGrabbedListener
{

    public function __construct()
    {
        //
    }

    public function handle(LinkGrabbedEvent $event)
    {
        $job = new GrabOffer($event->grabbedLink);
        $job->handle();
    }
}
