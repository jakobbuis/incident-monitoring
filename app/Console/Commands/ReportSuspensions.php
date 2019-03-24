<?php

namespace App\Console\Commands;

use App\Services\Slack;
use App\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReportSuspensions extends Command
{
    protected $signature = 'suspensions:report';
    protected $description = 'Report all suspensions to Slack';

    private $slack;

    public function __construct(Slack $slack)
    {
        parent::__construct();
        $this->slack = $slack;
    }

    public function handle()
    {
        $websites = Website::suspended()->get();

        if ($websites->count() === 0) {
            Log::info('No websites are currently suspended, a Slack report is not needed');
            return;
        }

        Log::info('Reporting suspended websites to Slack', ['websites' => $websites->pluck('id')->toArray()]);

        $message = "Reminder: monitoring is suspended for the following websites:\n";
        $items = $websites->map(function ($website) {
            $start = $website->monitoring_suspended->formatLocalized('%A %e %B %H:%M');
            return "• {$website->name} ($website->url) since {$start} UTC\n";
        });
        $message .= $items->implode('');
        $this->slack->sendNotification($message);
    }
}
