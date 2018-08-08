<?php

namespace App\Hue;

class Manager
{
    private $bridge;

    public function __construct()
    {
        $ip = config('hue.ip_address');
        $username = config('hue.username');
        $this->bridge = new \Phue\Client($ip, $username);
    }

    public function showAlert(string $type = 'red') : void
    {

    }
}
