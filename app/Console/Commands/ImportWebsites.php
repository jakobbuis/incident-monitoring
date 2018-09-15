<?php

namespace App\Console\Commands;

use App\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;
use League\Csv\Statement;

class ImportWebsites extends Command
{
    protected $description = 'Import a CSV-file with website information';

    protected $signature = 'websites:import
                                {file : file path to load}
                                {--name=Account : Column name for the name of the website}
                                {--url=URL : Column name for the URL}
                                {--filter-key=Status : Column name to filter by (requires --filter-value)}
                                {--filter-value=Productie : Column value to filter by (requires --filter-key)}
                                {--purge : Remove all websites from the system not listed in the imported file}';

    public function handle()
    {
        // Read the CSV file
        $file = $this->argument('file');
        if (!is_readable($file)) {
            $this->error('Cannot read input file');
            return;
        }
        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0);

        // Parse and filter all rows to [process
        $records = collect((new Statement)->process($csv));
        $records = $records->filter(function($record) {
            // Filter by the options given
            return $this->filter($record);
        })->map(function($record) {
            // Grab only the fields we need
            return $this->restructureRecord($record);
        })->filter(function($record) {
            // Do not create invalid records
            return $this->validRecord($record);
        });

        // Create or update websites
        $records->each(function($record) {
            $this->save($record);
        });

        // if --purge is given, purge all other websites not in the imported file
        if ($this->option('purge')) {
            $this->purgeOthers($records);
        }
    }

    /**
     * Filter the records by the filtering options given
     */
    private function filter(array $record) : bool
    {
        $key = $this->option('filter-key');
        $value = $this->option('filter-value');

        if (isset($record[$key]) && $record[$key] === $value) {
            return true;
        }

        Log::info('Skipping record: does not meet filter options', $record);
        return false;
    }

    /**
     * Standarise the record to make later operations easier
     */
    private function restructureRecord(array $record) : array
    {
        return [
            'name' => $record[$this->option('name')],
            'url' => $record[$this->option('url')],
        ];
    }

    /**
     * Filter out records which have incomplete or invalid information
     */
    private function validRecord(array $record) : bool
    {
        if (empty($record['name'])) {
            Log::info('Not processing record: name is empty', $record);
            return false;
        }

        if (empty($record['url'])) {
            Log::info('Not processing record: url is empty', $record);
            return false;
        }

        if (filter_var($record['url'], FILTER_VALIDATE_URL) === false) {
            Log::info('Not processing record: URL is not valid', $record);
            return false;
        }

        return true;
    }

    /**
     * Create or update the website with the new record
     */
    private function save(array $record) : void
    {
        $existingWebsite = Website::where('name', $record['name'])->first();

        if ($existingWebsite) {
            $existingWebsite->name = $record['name'];
            $existingWebsite->url = $record['url'];
            $existingWebsite->save();
            Log::info("Updated website {$existingWebsite->id} with new data", $record);
        } else {
            Website::create($record);
            Log::info("Created new website entry", $record);
        }
    }

    public function purgeOthers(Collection $records) : void
    {
        $names = $records->pluck('name');

        Website::all()->filter(function($website) use ($names) {
            return !$names->contains($website->name);
        })->each(function($website){
            Log::info("Deleting website {$website->id}: name is not in file imported with --purge");
            $website->delete();
        });
    }
}
