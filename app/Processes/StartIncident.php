<?php

namespace App\Processes;

use App\Incident;
use App\Services\Slack;
use App\Services\Twilio;
use App\User;
use App\Website;

class StartIncident
{
    private $twilio;
    private $slack;

    public function __construct(Twilio $twilio, Slack $slack)
    {
        $this->twilio = $twilio;
        $this->slack = $slack;
    }

    public function __invoke(Website $website, string $type, int $level, array $data = []) : void
    {
        if (!in_array($level, Incident::LEVELS)) {
            throw new \DomainException('Invalid incident level');
        }

        $existingIncident = $website->incidents()->where('type', $type)->first();
        if ($existingIncident) {
            return;
        }

        $incident = Incident::create([
            'website_id' => $website->id,
            'type' => $type,
            'level' => $level,
            'data' => $data,
        ]);

        $message = "{$incident->type} incident started on {$website->name} ({$website->url})";
        $phones = User::all()->pluck('phone_number')->filter();
        foreach ($phones as $phone) {
            $this->twilio->sendSMS($phone, $message);
        }

        $this->slack->sendNotification($message);
    }
}
