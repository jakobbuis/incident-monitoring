<?php

namespace App\Console\Commands;

use App\Incident;
use App\Processes\ResolveIncident;
use App\Processes\StartIncident;
use App\Website;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SiteResponse extends Command
{
    protected $signature = 'check:response';
    protected $description = 'Check websites if they are up or down';

    private $guzzle;
    private $startIncident;
    private $resolveIncident;

    public function __construct(Client $guzzle, StartIncident $startIncident, ResolveIncident $resolveIncident)
    {
        parent::__construct();

        $this->guzzle = $guzzle;
        $this->startIncident = $startIncident;
        $this->resolveIncident = $resolveIncident;
    }

    public function handle()
    {
        Website::all()->each([$this, 'checkStatus']);
    }

    public function checkStatus(Website $website)
    {
        try {
            $response = $this->guzzle->get($website->url, ['http_errors' => false]);
            $status = $response->getStatusCode();
        } catch (GuzzleException $e) {
            Log::info("Website {$website->name} responded to check with GuzzleException", [
                'exception' => $e,
            ]);
            // Check for certificate errors
            if (strpos($e->getMessage(), 'certificate') !== false) {
                $this->startIncident($website, 'CertificateError', Incident::LEVEL_IMPORTANT);
            } else {
                $this->startIncident($website, 'SiteDown', Incident::LEVEL_CRITICAL, [
                    'http_status_code' => null,
                ]);
            }
            return;
        }
        Log::info("Website {$website->name} responded to check with HTTP {$status}");

        if ($status >= 400) {
            $this->startIncident($website, 'SiteDown', Incident::LEVEL_CRITICAL, [
                'http_status_code' => $status,
            ]);
        } else {
            $this->resolveIncident($website, 'SiteDown');
            $this->resolveIncident($website, 'CertificateError');
        }
    }
}
