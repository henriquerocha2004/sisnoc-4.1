<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type_link');
            $table->string('bandwidth');
            $table->string('link_identification');
            $table->string('telecommunications_company');
            $table->string('monitoring_ip');
            $table->string('installed_router_model');
            $table->string('serial_router');
            $table->string('local_ip_router');
            $table->unsignedBigInteger('establishment_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('links');
    }
}
