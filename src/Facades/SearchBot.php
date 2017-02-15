<?php namespace Sukohi\SearchBot\Facades;

use Illuminate\Support\Facades\Facade;

class SearchBot extends Facade {

    /**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor() { return 'search-bot'; }

}