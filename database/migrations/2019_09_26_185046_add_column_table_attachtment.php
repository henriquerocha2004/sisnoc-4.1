<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTableAttachtment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attachment', function(Blueprint $table){
            $table->unsignedBigInteger('id_called');
            $table->foreign('id_called')->references('id')->on('called');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attachment', function(Blueprint $table){
            $table->dropForeign('attachment_id_called_foreign');
            $table->dropColumn('id_called');
        });
    }
}
