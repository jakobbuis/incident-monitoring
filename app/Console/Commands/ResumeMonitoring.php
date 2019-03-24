<?php

namespace App\Console\Commands;

use App\Website;
use Illuminate\Console\Command;

class ResumeMonitoring extends Command
{
    protected $signature = 'website:resume';
    protected $description = 'Resume monitoring for all websites';

    public function handle()
    {
        $websites = Website::suspended()->get();

        $websites->each(function($website) {
            $website->monitoring_suspended = null;
            $website->save();
            $this->info("Resumed monitoring for {$website->name}");
        });
    }
}
