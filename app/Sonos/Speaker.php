<?php

namespace App\Sonos;

use GuzzleHttp\Client;
use duncan3dc\Sonos\Devices\Discovery;
use duncan3dc\Sonos\Network;

class Speaker
{
    private $guzzle;

    const RED_ALERT = 'red';
    const YELLOW_ALERT = 'yellow';

    public function __construct()
    {
        $this->guzzle = new Client;
    }

    public function soundAlert(string $type) : void
    {
        $host = config('sonos.api');
        $room = config('sonos.room_name');
        $volume = config('sonos.volume');

        $clip = "{$type}_alert.mp3";

        $this->guzzle->get("{$host}/{$room}/clip/{$clip}/{$volume}");
    }
}
