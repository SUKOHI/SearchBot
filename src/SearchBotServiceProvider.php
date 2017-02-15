<?php namespace Sukohi\SearchBot;

use Illuminate\Support\ServiceProvider;

class SearchBotServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var  bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/search_bot.php' => config_path('search_bot.php'),
        ], 'config');
        $this->publishes([
            __DIR__.'/migrations/2017_02_14_193440_create_crawling_queues_table.php' => database_path('migrations/2017_02_14_193440_create_crawling_queues_table.php')
        ], 'migration');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('search-bot', function()
        {
            return new SearchBot;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['search-bot'];
    }

}