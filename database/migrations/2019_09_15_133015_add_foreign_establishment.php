<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignEstablishment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('establishment', function(Blueprint $table){
            $table->foreign('regional_manager_code')->references('id')->on('regional_manager');
            $table->foreign('technician_code')->references('id')->on('technical_manager');
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
        Schema::table('establishment', function(Blueprint $table){
            $table->dropForeign('establishment_regional_manager_code_foreign');
            $table->dropForeign('establishment_regional_technician_code_foreign');
            $table->dropForeign('establishment_regional_id_user_foreign');
        });
    }
}
