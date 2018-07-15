<?php

namespace App\Console\Commands;

use App\Incident;
use Illuminate\Console\Command;

class ClearIncidents extends Command
{
    protected $signature = 'incidents:clear';
    protected $description = 'Removes all current incidents, useful for debuggin';

    public function handle()
    {
        $incidents = Incident::all();
        $count = $incidents->count();

        Incident::all()->each->delete();

        echo "Purged {$count} incidents from the database\n";
    }
}
