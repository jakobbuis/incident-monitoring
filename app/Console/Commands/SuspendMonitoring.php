<?php

namespace App\Console\Commands;

use App\Website;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SuspendMonitoring extends Command
{
    protected $signature = 'website:suspend';
    protected $description = 'Suspend the monitoring for a website';

    public function handle()
    {
        // Find the website
        $name = $this->ask('Which website should we suspend?');
        $candidates = Website::where('name', 'LIKE', "%{$name}%")->get();
        if ($candidates->count() === 0) {
            $this->info("Cannot find any website with '{$name}' in its name");
            return;
        } elseif ($candidates->count() === 1) {
            $website = $candidates->first();
            if (!$this->confirm("Suspend {$website->name}?", true)) {
                return;
            }
        } else {
            $name = $this->choice('Which website?', $candidates->pluck('name')->toArray());
            $website = $candidates->first(function($candidate) use ($name) {
                return $candidate->name === $name;
            });
        }

        // Suspend the website
        $website->monitoring_suspended = Carbon::now();
        $website->save();

        $this->info("Suspended monitoring for {$website->name}");
    }
}
