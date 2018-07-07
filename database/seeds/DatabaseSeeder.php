<?php

use App\Incident;
use App\Website;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Website::create(['name' => 'IN10', 'url' => 'https://www.in10.nl']);
        Website::create(['name' => 'Jakob', 'url' => 'https://www.jakobbuis.nl']);
        $teapot = Website::create(['name' => 'Teapot', 'url' => 'https://httpstat.us/418']);

        Incident::create(['type' => 'SiteDown', 'data' => [], 'website_id' => $teapot->id]);
    }
}
