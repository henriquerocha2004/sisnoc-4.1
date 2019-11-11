<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableNotesEstablishment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes_establishment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('desc');
            $table->unsignedBigInteger('user_id');
            $table->date('validate');
            $table->timestamps();

            //Foreign
            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes_establishment', function (Blueprint $table) {
            $table->dropForeign('notes_establishment_user_id_foreign');
        });

        Schema::dropIfExists('notes_establishment');
    }
}
