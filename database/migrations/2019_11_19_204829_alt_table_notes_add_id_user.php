<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AltTableNotesAddIdUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes', function(Blueprint $table){
            $table->unsignedBigInteger('id_user')->default(1);
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes', function(Blueprint $table){
            $table->dropForeign('notes_id_user_foreign');
            $table->dropColumn('id_user');
        });
    }
}
