<?php

namespace App\Console\Commands;

use App\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;
use League\Csv\Statement;

class ImportWebsites extends Command
{
    protected $signature = 'websites:import {file}';
    protected $description = 'Import a CSV-file with website information';

    public function handle()
    {
        // Read the CSV file
        $file = $this->argument('file');
        if (!is_readable($file)) {
            $this->error('Cannot read input file');
            return;
        }
        $csv = Reader::createFromPath($file, 'r');

        // Process all rows
        $records = collect((new Statement)->process($csv));
        $records->filter(function($record){
            // Do not create invalid records
            return $this->validRecord($record);
        })->each(function($record){
            // Create all records
            $this->save($record);
        });
    }

    private function validRecord(array $record) : bool
    {
        if (!is_array($record)) {
            Log::info('Not processsing record: is not an array', $record);
            return false;
        }

        if (count($record) !== 2) {
            Log::info('Not processsing record: is not exactly two items', $record);
            return false;
        }

        if (filter_var($record[1], FILTER_VALIDATE_URL) === false) {
            Log::info('Not processing record: URL is not valid', $record);
            return false;
        }

        return true;
    }

    public function save(array $record) : void
    {
        $existingWebsite = Website::where('name', $record[0])->first();

        if ($existingWebsite) {
            $existingWebsite->name = $record[0];
            $existingWebsite->url = $record[1];
            $existingWebsite->save();
            Log::info("Updated website {$existingWebsite->id} with new data", $record);
        } else {
            Website::create([
                'name' => $record[0],
                'url' => $record[1],
            ]);
            Log::info("Created new website entry", $record);
        }
    }
}
