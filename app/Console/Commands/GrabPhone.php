<?php

namespace App\Console\Commands;

use App\Offer;
use Illuminate\Console\Command;

class GrabPhone extends Command
{

    protected $signature = 'grab:phone {offerId?}';
    protected $description = 'Grab offer\'s phone';

    public function handle()
    {
        $job = new \App\Jobs\GrabPhone(
            $this->getOffer(
                $this->argument('offerId')
            )
        );
        $job->handle();
    }

    private function getOffer($id = null)
    {
        return Offer::findOrFail($id);
    }

}
