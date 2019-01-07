<?php

namespace App\Console\Commands;

use App\Incident;
use Illuminate\Console\Command;

class ClearIncidents extends Command
{
    protected $signature = 'incidents:resolve';
    protected $description = 'Resolves all current incidents, regardless of their status';

    public function handle()
    {
        $ongoingIncidents = Incident::all()->filter(function($incident){
            return $incident->resolved_at === null;
        });

        $ongoingIncidents->each(function($incident){
            $incident->resolve();
        });

        echo "Forcefully resolved {$ongoingIncidents->count()} ongoing incidents\n";
    }
}
