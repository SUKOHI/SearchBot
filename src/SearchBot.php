<?php namespace Sukohi\SearchBot;

use Carbon\Carbon;
use Goutte\Client;

class SearchBot {

    private $client;

    public function __construct() {

        $this->client = new Client();

    }

    public function request($url, $options = []) {

        $type = array_get($options, 'type', 'main');
        $url_deletion = array_get($options, 'url_deletion', true);
        $crawling_queue = CrawlingQueue::orderBy('updated_at', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        if(!is_null($crawling_queue)) {

            $url = $crawling_queue->url;

            if($url_deletion) {

                CrawlingQueue::where('type', $type)
                    ->where('url', $url)
                    ->delete();

            } else {

                $crawling_queue->updated_at = Carbon::now();
                $crawling_queue->save();

            }

        }

        $crawling_result = new CrawlingResult();
        $crawling_result->setType($type);
        $crawling_result->setUrl($url);

        try {

            $crawler = $this->client->request('GET', $url);
            $crawling_result->setResponse($this->client->getResponse());
            $crawling_result->setCrawler($crawler);
            $crawling_result->setExists(true);

        } catch (\Exception $e) {

            $crawling_result->setExists(false);
            $crawling_result->setException($e);

        }

        return $crawling_result;

    }

}