<?php

namespace App\Console\Commands;

use App\GrabbedLink;
use Illuminate\Console\Command;

class GrabOffer extends Command
{

    protected $signature = 'grab:offer {grabbedLinkId?}';
    protected $description = 'Grab offer';

    public function handle()
    {
        $job = new \App\Jobs\GrabOffer(
            $this->getGrabbedLink(
                $this->argument('grabbedLinkId')
            )
        );
        $job->handle();
    }

    private function getGrabbedLink($id = null)
    {
        return GrabbedLink::findOrFail($id);
    }

}
