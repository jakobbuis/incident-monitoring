<?php

namespace App\Console\Commands;

use App\Services\Slack;
use App\Website;
use Illuminate\Console\Command;

class ResumeMonitoring extends Command
{
    protected $signature = 'website:resume';
    protected $description = 'Resume monitoring for all websites';

    private $slack;

    public function __construct(Slack $slack)
    {
        parent::__construct();
        $this->slack = $slack;
    }

    public function handle()
    {
        $websites = Website::suspended()->get();

        $websites->each(function($website) {
            // Unsuspend the website
            $website->monitoring_suspended = null;
            $website->save();

            // Report to console and slack
            $message = "Resumed monitoring for {$website->name}";
            $this->info($message);
            $this->slack->sendNotification($message);
        });
    }
}
