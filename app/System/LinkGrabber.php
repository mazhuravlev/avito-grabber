<?php

namespace App\System;


use Symfony\Component\DomCrawler\Crawler;

class LinkGrabber
{

    public static function grabLinks($html)
    {
        $crawler = new Crawler($html);
        return $crawler->filter('a.item-link')->extract('href');
    }

}