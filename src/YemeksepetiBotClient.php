<?php

namespace TCGunel\YemeksepetiBot;

use Illuminate\Support\Facades\Http;

class YemeksepetiBotClient
{
    /** @var Http */
    protected $http_client;

    /** @var string */
    protected $url;

    /**
     * YemeksepetiBot constructor.
     * @param Http|null $http_client
     * @param string $url
     */
    public function __construct($http_client, string $url)
    {
        if ($http_client instanceof Http === false) {

            $this->http_client = Http::class;

        } else {

            $this->http_client = $http_client;

        }

        $this->url = $url;
    }
}
