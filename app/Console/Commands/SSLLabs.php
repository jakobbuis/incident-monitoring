<?php

namespace App\Console\Commands;

use App\Processes\ResolveIncident;
use App\Processes\StartIncident;
use App\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SSLLabs extends Command
{
    protected $signature = 'check:ssllabs';
    protected $description = 'Check the SSL Labs Grade for the certificate';

    private $startIncident;
    private $resolveIncident;
    private $storage;

    public function __construct(StartIncident $startIncident, ResolveIncident $resolveIncident)
    {
        parent::__construct();

        $this->startIncident = $startIncident;
        $this->resolveIncident = $resolveIncident;

        $this->storage = Storage::disk('local');
    }

    public function handle()
    {
        $this->exportURLs();
        $this->executeCheck();
        $this->interpretResults();
        $this->cleanup();
    }

    private function exportURLs() : void
    {
        $urls = Website::all()->pluck('url');
        $export = $urls->implode(PHP_EOL);
        $this->storage->put('ssl_domains.txt', $export);
    }

    private function executeCheck() : void
    {
        $executable = app_path('ssllabs-scan');
        $input = storage_path('app/ssl_domains.txt');
        $output = storage_path('app/ssl_results.txt');
        $command = "{$executable} --quiet --grade --hostfile={$input} > {$output}";

        Log::info('Executing ssl labs check', ['command' => $command]);

        exec($command);
    }

    private function interpretResults() : void
    {
    }

    private function cleanup() : void
    {
        $this->storage->delete('ssl_domains.txt');
    }
}
