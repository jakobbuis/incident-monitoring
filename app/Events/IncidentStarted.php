<?php

namespace App\Events;

use App\Incident;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncidentStarted
{
    use Dispatchable, SerializesModels;

    public $incident;

    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
    }
}
