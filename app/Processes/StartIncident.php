<?php

namespace App\Processes;

use App\Incident;
use App\Website;

class StartIncident
{
    public function __invoke(Website $website, string $type, int $level, array $data = []) : void
    {
        if (!in_array($level, Incident::LEVELS)) {
            throw new \DomainException('Invalid incident level');
        }

        $website->incidents()->firstOrCreate(compact('type'), compact('level', 'data'));
    }
}
