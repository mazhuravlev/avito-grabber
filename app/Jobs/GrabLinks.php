<?php

namespace App\Jobs;

use App\GrabbedLink;
use App\System\LinkGrabber;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GrabLinks extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;


    private $force = false;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $options)
    {
        if (array_key_exists('force', $options) && $options['force']) {
            $this->force = true;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client(['base_uri' => 'https://m.avito.ru/respublika_krym/nedvizhimost']);
        $page = 0;
        while (true) {
            $newLinksGrabbed = 0;
            $response = $client->get('', ['query' => ['p' => $page, 'hist_back' => '1']]);
            $links = LinkGrabber::grabLinks($response->getBody()->getContents());
            foreach ($links as $linkHref) {
                /** @var GrabbedLink $link */
                $link = GrabbedLink::where(['href' => $linkHref])->first();
                if (!$link) {
                    GrabbedLink::create(['href' => $linkHref]);
                    $newLinksGrabbed++;
                }
            }

            printf('[PAGE: %d]grabbed: %d, new: %d' . PHP_EOL, $page, count($links), $newLinksGrabbed);
            if (0 === count($links)) {
                break;
            }
            if (!$this->force and 0 === $newLinksGrabbed) {
                break;
            }
            $page++;
        }

    }
}
