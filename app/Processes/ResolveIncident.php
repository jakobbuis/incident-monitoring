<?php

namespace App\Processes;

use App\Services\Slack;
use App\User;
use App\Website;
use Illuminate\Support\Facades\Log;

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

        Log::info("Resolving incident for <{$website->id}, {$type}>");
        $incident->resolve();

        $message = "{$incident->type} incident resolved on {$website->name} ({$website->url})";
        $this->slack->sendNotification($message);
    }
}
