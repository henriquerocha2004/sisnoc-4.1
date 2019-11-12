<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AltTableNotesEstablishmentAddEstablishmentCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes_establishment', function(Blueprint $table){
            $table->unsignedBigInteger('id_establishment');
            $table->foreign('id_establishment')->references('id')->on('establishment');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes_establishment', function(Blueprint $table){
            $table->dropForeign('notes_establishment_notes_establishment_foreign');
            $table->dropColumn('id_establishment');
        });
    }
}
