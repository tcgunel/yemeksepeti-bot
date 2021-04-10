<?php

namespace TCGunel\YemeksepetiBot;

use Illuminate\Support\ServiceProvider;

class YemeksepetiBotServiceProvider extends ServiceProvider
{
    /**
     * Publishes configuration file.
     *
     * @return  void
     */
    public function boot()
    {
    }

    /**
     * Make config publishment optional by merging the config from the package.
     *
     * @return  void
     */
    public function register()
    {
        $this->app->bind('BizimHesapB2b', function($app) {
            return new YemeksepetiBot();
        });
    }
}
