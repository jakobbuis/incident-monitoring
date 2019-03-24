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

    /**
     * Try every website 3 times to give a valid response, trigger an incident
     * when all three checks fail
     */
    public function handle()
    {
        $websites = Website::all();

        // Check all websites
        $websites = $websites->filter(function ($website) {
            return !$this->check($website);
        });

        // Check all failing websites again after a short delay
        sleep(60);
        $websites = $websites->filter(function ($website) {
            return !$this->check($website);
        });

        // Check all failing websites again after a short delay
        // create an incident if this third check fails as well
        sleep(60);
        $websites->each(function ($website) {
            $this->checkAndStartIncident($website);
        });
    }

    /**
     * Check a URL for a valid response
     * @param  string $url
     * @return bool
     */
    private function check(Website $website) : bool
    {
        // Prevent a RuntimeError by aliasing to local scope variables
        $resolveIncident = $this->resolveIncident;

        try {
            $response = $this->guzzle->get($website->url, ['http_errors' => false]);
            $status = $response->getStatusCode();
        } catch (GuzzleException $e) {
            Log::info("Website {$website->name} responded to check with GuzzleException", [
                'exception' => $e,
            ]);
            return false;
        }

        Log::info("Website {$website->name} responded to check with HTTP {$status}");

        if ($status < 400) {
            $resolveIncident($website, 'SiteDown');
            $resolveIncident($website, 'CertificateError');
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check a website for a valid response, starting an incident if the website
     * fails this third check again
     * @param  Website $website
     * @return void
     */
    private function checkAndStartIncident(Website $website) : void
    {
        // Prevent a RuntimeError by aliasing to local scope variables
        $start = $this->startIncident;
        $resolve = $this->resolveIncident;

        try {
            $response = $this->guzzle->get($website->url, ['http_errors' => false]);
            $status = $response->getStatusCode();
        } catch (GuzzleException $e) {
            Log::info("Website {$website->name} responded to check with GuzzleException", [
                'exception' => $e,
            ]);
            // Check for certificate errors
            if (strpos($e->getMessage(), 'certificate') !== false) {
                $start($website, 'CertificateError', Incident::LEVEL_IMPORTANT);
            } else {
                $start($website, 'SiteDown', Incident::LEVEL_CRITICAL, [
                    'http_status_code' => null,
                ]);
            }
            return;
        }
        Log::info("Website {$website->name} responded to check with HTTP {$status}");

        if ($status >= 400) {
            $start($website, 'SiteDown', Incident::LEVEL_CRITICAL, [
                'http_status_code' => $status,
            ]);
        } else {
            $resolve($website, 'SiteDown');
            $resolve($website, 'CertificateError');
        }
    }
}
