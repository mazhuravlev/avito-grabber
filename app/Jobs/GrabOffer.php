<?php

namespace App\Jobs;

use App\Events\OfferGrabbedEvent;
use App\GrabbedLink;
use App\Offer;
use App\Phone;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
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
        $offer->id = self::getText($crawler, '#item_id');
        $offer->price_string = self::getText($crawler, 'span[itemprop=price]');
        $offer->description = self::getText($crawler, 'div[itemprop=description]');
        $offer->title = self::getText($crawler, 'h1[itemprop=name]');
        $offer->address = self::getText($crawler, 'div#map');
        $offer->user_address = self::getText($crawler, 'div[itemprop=address]');
        $offer->cat_path = $crawler->filter('.breadcrumb-link')->last()->attr('href');
        /** @var Offer $offer */
        $offer = $this->grabbedLink->offer()->save($offer);
        Event::fire(new OfferGrabbedEvent($offer));
    }

    private static function getText(Crawler $crawler, $selector)
    {
        try {
            return trim($crawler->filter($selector)->text());
        } catch (\InvalidArgumentException $e) {
            return null;
        }
    }


}
