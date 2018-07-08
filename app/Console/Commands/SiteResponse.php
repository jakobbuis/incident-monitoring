<?php

namespace App\Console\Commands;

use App\Incident;
use App\Website;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SiteResponse extends Command
{
    protected $signature = 'check:response';
    protected $description = 'Check websites if they are up or down';

    private $guzzle;

    public function __construct(Client $guzzle)
    {
        parent::__construct();

        $this->guzzle = $guzzle;
    }

    public function handle()
    {
        Website::all()->each([$this, 'checkStatus']);
    }

    public function checkStatus(Website $website)
    {
        $response = $this->guzzle->get($website->url, ['http_errors' => false]);
        $status = $response->getStatusCode();
        Log::info("Website {$website->name} responded to check with HTTP {$status}");

        if ($status >= 400) {
            $website->startIncident('SiteDown', Incident::LEVEL_CRITICAL, (object) [
                'http_status_code' => $status,
            ]);
        } else {
            $website->resolveIncident('SiteDown');
        }
    }
}
