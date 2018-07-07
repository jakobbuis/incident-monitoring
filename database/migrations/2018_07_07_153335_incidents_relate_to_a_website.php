<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncidentsRelateToAWebsite extends Migration
{
    public function up()
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->integer('website_id')->unsigned();

            $table->foreign('website_id')
                ->references('id')->on('websites')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropForein(['website', 'id']);
            $table->dropColumn('website_id');
        });
    }
}
