<?php

namespace App\Hue;

use Phue\Command\SetLightState;

class Bulb
{
    private $bridge;
    private $id;

    public function __construct($bridge, $id)
    {
        $this->bridge = $bridge;
        $this->id = $id;
    }

    public function on()
    {
        $command = $this->defaultCommand()->on(true);
        $this->bridge->sendCommand($command);
    }

    public function off()
    {
        $command = $this->defaultCommand()->on(false);
        $this->bridge->sendCommand($command);
    }

    public function colour($hue, $saturation)
    {
        $command = $this->defaultCommand()->hue($hue)->saturation($saturation);
        $this->bridge->sendCommand($command);
    }

    private function defaultCommand()
    {
        $command = new SetLightState($this->id);
        return $command->brightness(128)->transitionTime(3);
    }
}
