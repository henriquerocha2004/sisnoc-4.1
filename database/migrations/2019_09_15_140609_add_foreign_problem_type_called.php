<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignProblemTypeCalled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problem_type_called', function (Blueprint $table) {
            $table->foreign('id_called')->references('id')->on('sub_caller');
            $table->foreign('id_problem_type')->references('id')->on('problem_type_called');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problem_type_called', function (Blueprint $table) {
            $table->dropForeign('problem_type_called_id_called_foreign');
            $table->dropForeign('problem_type_called_id_problem_type_foreign');
        });
    }
}
