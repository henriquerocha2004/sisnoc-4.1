<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEstabilishmentHolyday extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('establishment', function(Blueprint $table){
            $table->dropColumn('holiday');
            $table->date('holyday');
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
            $$table->dropColumn('holyday');
            $table->dateTime('holiday');
        });
    }
}
