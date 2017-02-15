# SearchBot
Laravel package to crawl websites.(Laravel 5+)


# Requirements

* [FriendsOfPHP/Goutte](https://github.com/FriendsOfPHP/Goutte)
* [SUKOHI/laravel-absolute-url](https://github.com/SUKOHI/laravel-absolute-url)

# Installation

Execute the next command.

    composer require sukohi/search-bot:1.*

Set the service providers in app.php

    'providers' => [
        ...Others...,
        Sukohi\SearchBot\SearchBotServiceProvider::class,
        Sukohi\LaravelAbsoluteUrl\LaravelAbsoluteUrlServiceProvider::class, 
    ]

Also alias

    'aliases' => [
        ...Others...,
        'LaravelAbsoluteUrl' => Sukohi\LaravelAbsoluteUrl\Facades\LaravelAbsoluteUrl::class,
        'SearchBot' => Sukohi\SearchBot\Facades\SearchBot::class,
    ]

Then execute the next commands.  

    php artisan vendor:publish
    php artisan migrate

Now you have `config/search_bot.php` which you can set domains restrictions.

# Config

    return [
    
        'main' => '*',
        'yahoo' => ['yahoo.com', 'www.yahoo.com'],
        'reddit' => ['www.reddit.com']
    
    ];

* If you don't need to set restriction, set `*`.

# Usage

    $starting_url = 'http://yahoo.com';
    $options = [
        'type' => 'main', // $type is optional.(Default: main),
        'url_deletion' => true  // Default: true
    ];
    $result = \SearchBot::request($starting_url, $options);

    if($result->exists()) {

        // Symfony\Component\BrowserKit\Response
        // See http://api.symfony.com/2.3/Symfony/Component/BrowserKit/Response.html
        $response = $result->response();

        // Symfony\Component\DomCrawler/Crawler
            // See http://api.symfony.com/2.3/Symfony/Component/DomCrawler/Crawler.html
        $crawler = $result->crawler();

        $result->links(function($crawler_queue, $url, $text){

            // All links including URL & text will come here.
            // So you can add your own code here like if you should add queue or not.
            // $crawler_queue has already type and url.

            $crawler_queue->save();

        });

    } else {

        $e = $result->exception();
        echo $e->getMessage();
        $type = $result->type();
        $url = $result->url();

    }

# Options

* type

    Type is string that you can decide freely.  
    Default is `main`.

* url_deletion

    If true here, URL accessed will be removed from DB.  
    Default is `true`.

# License

This package is licensed under the MIT License.  
Copyright 2017 Sukohi Kuhoh