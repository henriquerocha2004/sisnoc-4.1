<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignActionTakenCalled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('action_taken_called', function (Blueprint $table) {
            $table->foreign('id_caller')->references('id')->on('sub_caller');
            $table->foreign('id_action_taken')->references('id')->on('action_taken');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('action_taken_called', function (Blueprint $table) {
            $table->dropForeign('action_taken_called_id_caller_foreign');
            $table->dropForeign('action_taken_called_id_action_taken_foreign');
        });
    }
}
