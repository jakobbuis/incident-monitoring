<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncidentDataIsDefaultEmpty extends Migration
{
    public function up()
    {
        Schema::table('incidents', function(Blueprint $table){
            $table->json('data')->nullable(true)->change();
        });
    }

    public function down()
    {
        Schema::table('incidents', function(Blueprint $table){
            $table->json('data')->nullable(false)->change();
        });
    }
}
