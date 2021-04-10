<?php

namespace TCGunel\YemeksepetiBot;

use TCGunel\YemeksepetiBot\Exceptions\YemeksepetiBotException;

trait HandleErrors
{
    /**
     * @param \Illuminate\Http\Client\Response $response
     * @throws YemeksepetiBotException
     */
    protected function checkForErrors($response)
    {
        $body = collect(json_decode($response->body(), true));

        $error = "";

        if ($body->contains("resultCode") && $body->get("resultCode") == 0) {

            $error = $body->get("errorText");

        }

        if ($body->has("Message")) {

            $error = $body->get("Message");

        }

        if ($body->has("error") && !empty($body->get("error"))) {

            $error = $body->get("error");

        }

        if (!empty($error)) {

            throw new YemeksepetiBotException($error);

        }
    }
}
