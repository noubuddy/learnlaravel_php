<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\DomCrawler\Crawler;

class WumoComics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wumo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $comics = [];
        $i = 0;
        $url = "http://wumo.com/wumo";
        while ($i < 10) {
            $body = $this->cacheOrGet($url);
            $crawler = new Crawler($body);
            $imgEl = $crawler->filter('.box-content>a>img');
            $src = $imgEl->attr('src');
            $url = "http://wumo.com" . $crawler->filter('a.prev')->attr('href');
            $comics[] = [$src];
            $i++;
            //sleep(1);
        }
        var_dump($comics);
    }

    public function cacheOrGet($url)
    {
        if (Cache::has($url)) {
            return Cache::get($url);
        }

        $guzzle = new Client();
        $response = $guzzle->get($url);
        $body = $response->getBody()->getContents();
        Cache::put($url, $body);
        return $body;
    }
}
