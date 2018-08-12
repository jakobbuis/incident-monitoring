<?php

namespace App\Listeners;

use App\Events\IncidentStarted;
use App\Hue\Lamp;
use App\Incident;
use App\Sonos\Speaker;

/**
 * Fire the lights and sirens for major incidents
 */
class LightsAndSirens
{
    public function handle(IncidentStarted $event)
    {
        $incident = $event->incident;

        $lamp = new Lamp;
        $speaker = new Speaker;

        if ($incident->level === Incident::LEVEL_CRITICAL) {
            $speaker->soundAlert(Speaker::RED_ALERT);
            $lamp->showAlert(Lamp::RED_ALERT);
        } elseif ($incident->level === Incident::LEVEL_IMPORTANT) {
            $speaker->soundAlert(Speaker::YELLOW_ALERT);
            $lamp->showAlert(Lamp::YELLOW_ALERT);
        }
    }
}
