<?php

namespace Bundle\AWeekOfSymfonyBundle\Scraper;

use Goutte\Client;

class RecentlyEntriesScraper
{
    public function scrape()
    {
        $uri = 'http://www.symfony-project.org/blog/category/A+week+of+symfony';

        $client = new Client();
        $crawler = $client->request('GET', $uri);

        $links = $crawler->filter('#content1 h2 a')->links();

        return $links;
    }
}
