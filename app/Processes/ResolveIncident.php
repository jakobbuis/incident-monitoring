<?php

namespace App\Processes;

use App\Website;

class ResolveIncident
{
    public function __invoke(Website $website, string $type) : void
    {
        $incident = $website->incidents()->where('type', $type)->first();
        if ($incident) {
            $incident->resolve();
        }
    }
}
