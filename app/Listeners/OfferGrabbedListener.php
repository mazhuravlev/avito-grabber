<?php

namespace App\Listeners;

use App\Events\OfferGrabbedEvent;
use App\Jobs\GrabPhone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OfferGrabbedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OfferGrabbedListener $event
     * @return void
     */
    public function handle(OfferGrabbedEvent $event)
    {
        $job = new GrabPhone($event->offer);
        $job->handle();
    }
}
