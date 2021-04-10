<?php

namespace TCGunel\YemeksepetiBot\Tests;

use Illuminate\Support\Facades\Http;
use TCGunel\YemeksepetiBot\YemeksepetiBot;
use TCGunel\YemeksepetiBot\YemeksepetiBotServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public $faker;

    public $bot;

    public $html_content;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = \Faker\Factory::create("tr_TR");

        $http_client = Http::fake(function ($request) {

            return Http::response($this->html_content);

        });

        $this->bot = new YemeksepetiBot($http_client, "");
    }

    protected function getPackageProviders($app): array
    {
        return [
            YemeksepetiBotServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
    }


}
