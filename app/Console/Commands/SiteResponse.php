<?php

namespace App\Console\Commands;

use App\Incident;
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
        try {
            $response = $this->guzzle->get($website->url, ['http_errors' => false]);
            $status = $response->getStatusCode();
        } catch (GuzzleException $e) {
            Log::info("Website {$website->name} responded to check with GuzzleException", [
                'exception' => $e,
            ]);
            // Check for certificate errors
            if (strpos($e->getMessage(), 'certificate') !== false) {
                $website->startIncident('CertificateError', Incident::LEVEL_IMPORTANT);
            } else {
                $website->startIncident('SiteDown', Incident::LEVEL_CRITICAL, (object) [
                    'http_status_code' => null,
                ]);
            }
            return;
        }
        Log::info("Website {$website->name} responded to check with HTTP {$status}");

        if ($status >= 400) {
            $website->startIncident('SiteDown', Incident::LEVEL_CRITICAL, (object) [
                'http_status_code' => $status,
            ]);
        } else {
            $website->resolveIncident('SiteDown');
            $website->resolveIncident('CertificateError');
        }
    }
}
