<?php

namespace App\Processes;

use App\Services\Slack;
use App\User;
use App\Website;

class ResolveIncident
{
    private $slack;

    public function __construct(Slack $slack)
    {
        $this->slack = $slack;
    }

    public function __invoke(Website $website, string $type) : void
    {
        $incident = $website->incidents()->ongoing()->where('type', $type)->first();
        if (!$incident) {
            return;
        }

        $incident->resolve();

        $message = "{$incident->type} incident resolved on {$website->name} ({$website->url})";
        $this->slack->sendNotification($message);
    }
}
