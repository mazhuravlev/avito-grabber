<?php

namespace App\Listeners;

use App\Events\LinkGrabbed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LinkGrabbed
{

    public function __construct()
    {
        //
    }

    public function handle(LinkGrabbed $event)
    {
        //
    }
}
