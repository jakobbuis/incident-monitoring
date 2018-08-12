<?php

namespace App\Hue;

use Phue\Command\SetLightState;

class Lamp
{
    private $bridge;
    private $id;

    const RED_ALERT = 0;
    const YELLOW_ALERT = 12750;

    public function __construct()
    {
        $ip = config('hue.ip_address');
        $username = config('hue.username');
        $this->bridge = new \Phue\Client($ip, $username);

        $this->id = config('hue.lamp_id');
    }

    public function showAlert(int $type) : void
    {
        $command = (new SetLightState($this->id));

        // define two states to alternate between
        $up = (clone $command)->hue($type)->brightness(255)->transitionTime(2);
        $down = (clone $command)->hue($type)->brightness(128)->transitionTime(2);

        // lamp in down state and on
        $this->bridge->sendCommand((clone $down)->on());

        // alternate light between states
        for ($i=0; $i < 3; $i++) {
            $this->bridge->sendCommand($up);
            sleep(2);
            $this->bridge->sendCommand($down);
            sleep(2);
        }

        // lamp off
        $this->bridge->sendCommand((clone $command)->off());
    }
}
