<?php namespace Sukohi\SearchBot;

class CrawlingResult {

    private $_type,
            $_url,
            $_response,
            $_crawler,
            $_exception,
            $_exists = false;

    public function setUrl($url) {

        $this->_url = $url;

    }

    public function setType($type) {

        $this->_type = $type;

    }

    public function setResponse($response) {

        $this->_response = $response;

    }

    public function setCrawler($crawler) {

        $this->_crawler = $crawler;

    }

    public function setException($e) {

        $this->_exception = $e;

    }

    public function setExists($bool) {

        $this->_exists = $bool;

    }

    public function links($callback) {

        if(is_callable($callback)) {

            $domains = config('search_bot.domain.'. $this->_type, []);

            if(!is_array($domains)) {

                $domains = [$domains];

            }

            $absolute_path = \LaravelAbsoluteUrl::baseUrl($this->_url);
            $this->_crawler->filter('a')->each(function ($node) use($absolute_path, $domains, $callback) {

                $href = $node->attr('href');
                $url = $absolute_path->get($href);
                $text = $node->text();
                $host = parse_url($url, PHP_URL_HOST);

                if(in_array('*', $domains) || in_array($host, $domains)) {

                    $exists = CrawlingQueue::where('type', $this->_type)
                        ->where('url', $url)
                        ->exists();

                    if(!$exists && is_callable($callback)) {

                        $crawler_queue = new CrawlingQueue();
                        $crawler_queue->type = $this->_type;
                        $crawler_queue->url = $url;
                        $callback($crawler_queue, $url, $text);

                    }

                }

            });

        }

    }

    public function response() {

        return $this->_response;

    }

    public function crawler() {

        return $this->_crawler;

    }

    public function type() {

        return $this->_type;

    }

    public function url() {

        return $this->_url;

    }

    public function exists() {

        return $this->_exists;

    }

    public function exception() {

        return $this->_exception;

    }

}