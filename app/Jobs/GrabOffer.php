<?php

namespace App\Jobs;

use App\GrabbedLink;
use App\Offer;
use App\Phone;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\DomCrawler\Crawler;

class GrabOffer extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $grabbedLink;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GrabbedLink $grabbedLink)
    {
        $this->grabbedLink = $grabbedLink;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Client $client = null)
    {
        if (is_null($client)) {
            $client = new Client(
                ['base_uri' => 'http://avito.ru']
            );
        }
        $response = $client->get($this->grabbedLink->href);
        $crawler = new Crawler($response->getBody()->getContents());
        $offer = new Offer();
        $offer->id = $crawler->filter('#item_id')->text();
        $offer->price_string = $crawler->filter('span[itemprop=price]')->text();
        $offer->description = $crawler->filter('div[itemprop=description]')->text();
        $offer->title = $crawler->filter('h1[itemprop=name]')->text();
        $offer->address = $crawler->filter('div#map')->text();
        if ($locationNode = $crawler->filter('div[itemprop=address]')->first()) {
            $offer->user_address = $locationNode->text();
        }
        $offer->cat_path = $crawler->filter('.breadcrumb-link')->last()->attr('href');
        $this->grabbedLink->offer()->save($offer);


    }


}
