<?php

namespace App\Sonos;

use GuzzleHttp\Client;
use duncan3dc\Sonos\Devices\Discovery;
use duncan3dc\Sonos\Network;

class Speaker
{
    private $guzzle;

    public function __construct()
    {
        $this->guzzle = new Client;
    }

    public function alert(string $level = 'red') : void
    {
        $host = config('sonos.api');
        $room = config('sonos.room_name');
        $volume = config('sonos.volume');

        $clip = "{$level}_alert.mp3";

        $this->guzzle->get("{$host}/{$room}/clip/{$clip}/{$volume}");
    }
}
