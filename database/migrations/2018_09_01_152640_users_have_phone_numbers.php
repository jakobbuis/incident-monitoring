<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersHavePhoneNumbers extends Migration
{
    public function up()
    {
        Schema::table('users', function(Blueprint $table){
            $table->string('phone_number')->default(null)->nullable(true);
        });
    }

    public function down()
    {
        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('phone_number');
        });
    }
}
