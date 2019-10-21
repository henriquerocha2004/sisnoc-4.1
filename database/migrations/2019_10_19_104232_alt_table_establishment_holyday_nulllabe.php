<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltTableEstablishmentHolydayNulllabe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('establishment', function(Blueprint $table){
            DB::statement("ALTER TABLE sisnoc.establishment CHANGE COLUMN holyday holyday DATE NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('establishment', function(Blueprint $table){
            DB::statement(" ALTER TABLE sisnoc.establishment CHANGE COLUMN holyday holyday DATE NOT NULL");
        });
    }
}
