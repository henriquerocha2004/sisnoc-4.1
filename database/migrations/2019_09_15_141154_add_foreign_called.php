<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignCalled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('called', function(Blueprint $table){
            $table->foreign('id_establishment')->references('id')->on('establishment');
            $table->foreign('id_link')->references('id')->on('links');
            $table->foreign('id_problem_cause')->references('id')->on('problem_cause');
            $table->foreign('id_user_open')->references('id')->on('users');
            $table->foreign('id_user_close')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('called', function (Blueprint $table) {
            $table->dropForeign('called_id_establishment_foreign');
            $table->dropForeign('called_id_link_foreign');
            $table->dropForeign('called_id_problem_cause_foreign');
            $table->dropForeign('called_id_user_open_foreign');
            $table->dropForeign('called_id_user_close_foreign');
        });
    }
}
