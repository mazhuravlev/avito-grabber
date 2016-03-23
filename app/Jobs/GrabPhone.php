<?php

namespace App\Jobs;

use App\Offer;
use App\Phone;
use App\System\ProxyClient;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class GrabPhone extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $offer;

    private static $sleepTimeout = 2;

    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    public function handle()
    {
        $client = new ProxyClient(new Client());
        $href = 'https://m.avito.ru' . $this->offer->grabbedLink->href;
        $response = $client->get($href);
        $crawler = new Crawler($response->getBody()->getContents());
        $phoneLink = 'https://m.avito.ru' . $crawler->filter('a.action-show-number')->attr('href');
        sleep(self::$sleepTimeout);
        $response = $client->get(
            $phoneLink . '?async',
            [
                'headers' => [
                    'Accept' => 'application/json, text/javascript, */*; q=0.01',
                    'Referer' => $href,
                ]
            ]
        );
        $phoneJson = json_decode($response->getBody()->getContents(), true);
        $phoneNumber = self::formatPhone($phoneJson['phone']);
        /** @var Phone $phone */
        $phone = Phone::firstOrCreate(['id' => $phoneNumber]);
        try {
            $this->offer->phones()->attach($phone);
        } catch (QueryException $e) {
            if ('23000' === $e->getCode() and 1062 === $e->errorInfo[1]) {
                // duplicate, ignore
            } else {
                throw $e;
            }
        }
    }

    private static function formatPhone($phone)
    {
        $numbers = preg_replace('/\D/', '', $phone);
        if (preg_match('/^7\d{10}$/', $numbers)) {
            return $numbers;
        } elseif (preg_match('/^8\d{10}$/', $numbers)) {
            return preg_replace('/^8/', '7', $numbers);
        } elseif (preg_match('/^380\d{9}$/', $numbers)) {
            return $numbers;
        } elseif (preg_match('/^0\d{9}$/', $numbers)) {
            return '38' . $numbers;
        } else {
            Log::error('invalid phone format: ' . $phone);
            throw new \ErrorException($numbers);
        }
    }

}
