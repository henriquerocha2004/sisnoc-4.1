<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableEstablishmentAddFts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('establishment', function(Blueprint $table){
            DB::statement("ALTER TABLE sisnoc.establishment ADD FULLTEXT (establishment_code, address, neighborhood, city, state, manager_name)");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
