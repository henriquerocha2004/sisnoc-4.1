<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignSubcaller extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_caller', function (Blueprint $table) {
            $table->foreign('id_caller')->references('id')->on('called');
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
        Schema::table('sub_caller', function (Blueprint $table) {
            $table->dropForeign('sub_caller_id_caller_foreign');
            $table->dropForeign('sub_caller_id_id_user_foreign');
        });
    }
}
