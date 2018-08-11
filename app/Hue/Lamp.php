<?php

namespace App\Hue;

use Phue\Command\SetLightState;

class Lamp
{
    private $bridge;
    private $id;

    public function __construct()
    {
        $ip = config('hue.ip_address');
        $username = config('hue.username');
        $this->bridge = new \Phue\Client($ip, $username);

        $this->id = config('hue.lamp_id');
    }

    private function showAlert(string $level = 'red') : void
    {
        // lamp on
        // set lamp to desired colour, low brightness
        // repeat 5 times
            // set lamp to full brightness, transition 2 seconds
            // sleep 1.9 seconds
            // set lamp to low brightness, transition 2 seconds
            // sleep 1.9 seconds
        // lamp off
    }

    // private function on()
    // {
    //     $command = $this->defaultCommand()->on(true);
    //     $this->bridge->sendCommand($command);
    // }

    // private function off()
    // {
    //     $command = $this->defaultCommand()->on(false);
    //     $this->bridge->sendCommand($command);
    // }

    // public function colour($hue, $saturation)
    // {
    //     $command = $this->defaultCommand()->hue($hue)->saturation($saturation);
    //     $this->bridge->sendCommand($command);
    // }

    private function defaultCommand()
    {
        $command = new SetLightState($this->id);
        return $command->brightness(128)->transitionTime(3);
    }
}
