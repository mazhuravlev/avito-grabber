<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GrabLinks extends Command
{

    protected $signature = 'grab:links {--force}';
    protected $description = 'Grab links';

    public function handle()
    {
        $job = new \App\Jobs\GrabLinks(
            [
                'force' => $this->option('force')
            ]
        );
        $job->handle();
    }

}
