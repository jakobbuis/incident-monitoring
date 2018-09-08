<?php

namespace App\Services;

class Slack
{
    private $guzzle;

    public function __construct(\GuzzleHttp\Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public function sendNotification(string $text) : void
    {
        $this->guzzle->post(config('slack.webhook_url'), [
            'json' => [
                'text' => $text,
            ],
        ]);
    }
}
