<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForeignProblemCause extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('called', function(Blueprint $table){
            $table->dropForeign('called_id_problem_cause_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('called', function(Blueprint $table){
            $table->foreign('id_problem_cause')->references('id')->on('problem_cause');
        });
    }
}
