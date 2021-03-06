<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\DomCrawler\Crawler;

class ChuckNorrisJoke extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'joke:norris';

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
     * @return string
     */
    public function handle()
    {
        $comics = [];
        for ($i = 2568; $i > 2558; $i--) {
            $body = $this->cacheorGet('https://xkcd.com/' . $i);
            $crawler = new Crawler($body);
            $imgEl = $crawler->filter('#comic>img');
            $src = $imgEl->attr('src');
            $title = $imgEl->attr('alt');
            $text = $imgEl->attr('title');
            $comics[] = [$src, $title, $text];
            sleep(1);
        }
        var_dump($comics);
    }

    public function cacheorGet($url)
    {
        if(Cache::has($url)) {
            return Cache::get($url);
        }

        $guzzle = new Client();
        $response = $guzzle->get($url);
        $body = $response->getBody()->getContents();
        Cache::put($url, $body);
        return $body;
    }
}
