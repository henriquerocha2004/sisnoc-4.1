<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubCallerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_caller', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_caller');
            $table->integer('status');
            $table->unsignedBigInteger('id_user');
            $table->integer('sisman')->nullable();
			$table->integer('otrs')->nullable();
			$table->string('call_telecommunications_company_number', 30)->nullable();
			$table->dateTime('deadline')->nullable();
			$table->dateTime('hr_open_call_telecommunications_company')->nullable();
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
        Schema::dropIfExists('sub_caller');
    }
}
