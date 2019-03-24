<?php

namespace App\Console\Commands;

use App\Website;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SuspendMonitoring extends Command
{
    protected $signature = 'website:suspend {id}';
    protected $description = 'Suspend the monitoring for a website';

    public function handle()
    {
        // Find the website
        $id = $this->argument('id');
        $website = Website::find($id);
        if (!$website) {
            $this->error("Cannot find website with ID {$id}");
            return;
        }

        // Suspend the website
        $website->monitoring_suspended = Carbon::now();
        $website->save();
    }
}
