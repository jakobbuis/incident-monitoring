<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class Slack
{
    private $guzzle;

    public function __construct(\GuzzleHttp\Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public function sendNotification(string $text) : void
    {
        $webhook = config('slack.webhook_url');
        if (empty($webhook)) {
            Log::info('Not updating Slack-channel: no webhook URL is set');
        }

        $this->guzzle->post($webhook, [
            'json' => [
                'text' => $text,
            ],
        ]);
    }
}
