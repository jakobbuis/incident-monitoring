<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsingSoftdeletes extends Migration
{
    public function up()
    {
        Schema::table('websites', function ($table) {
            $table->softDeletes();
        });

        Schema::table('incidents', function ($table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('websites', function ($table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('incidents', function ($table) {
            $table->dropColumn('deleted_at');
        });
    }
}
