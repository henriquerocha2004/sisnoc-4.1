<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltTableLinksModelTouter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('links', function(Blueprint $table){
           DB::statement("ALTER TABLE sisnoc.links CHANGE COLUMN serial_router serial_router VARCHAR(191) NULL");
           DB::statement("ALTER TABLE sisnoc.links CHANGE COLUMN installed_router_model installed_router_model VARCHAR(191) NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('links', function(Blueprint $table){
           DB::statement("ALTER TABLE sisnoc.links CHANGE COLUMN serial_router serial_router VARCHAR(191) NOT NULL");
           DB::statement("ALTER TABLE sisnoc.links CHANGE COLUMN installed_router_model installed_router_model VARCHAR(191) NOT NULL");
        });
    }
}
