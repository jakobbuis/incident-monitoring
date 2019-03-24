<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MonitoringCanBeSuspended extends Migration
{
    public function up()
    {
        Schema::table('websites', function(Blueprint $table) {
            $table->datetime('monitoring_suspended')->nullable(true)->default(null);
        });
    }

    public function down()
    {
        Schema::table('websites', function(Blueprint $table) {
            $table->dropColumn('monitoring_suspended');
        });
    }
}
