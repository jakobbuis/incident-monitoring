<?php

namespace App\Processes;

use App\Incident;
use App\Services\Slack;
use App\User;
use App\Website;
use Illuminate\Support\Facades\Log;

class StartIncident
{
    private $slack;

    public function __construct(Slack $slack)
    {
        $this->slack = $slack;
    }

    public function __invoke(Website $website, string $type, int $level, array $data = []) : void
    {
        if (!in_array($level, Incident::LEVELS)) {
            throw new \DomainException('Invalid incident level');
        }

        $existingIncident = $website->incidents()->ongoing()->where('type', $type)->first();
        if ($existingIncident) {
            Log::info("Not starting new incident for <$website, $type>, because an incident is ongoing");
            return;
        }

        Log::info("Starting new incident for <$website, $type>");
        $incident = Incident::create([
            'website_id' => $website->id,
            'type' => $type,
            'level' => $level,
            'data' => $data,
        ]);

        $message = "{$incident->type} incident started on {$website->name} ({$website->url})";
        $this->slack->sendNotification($message);
    }
}
