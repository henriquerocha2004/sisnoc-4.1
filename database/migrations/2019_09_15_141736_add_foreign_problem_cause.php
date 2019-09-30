<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignProblemCause extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problem_cause', function (Blueprint $table) {
            $table->foreign('id_category')->references('id')->on('category_problem');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problem_cause', function (Blueprint $table) {
            $table->dropForeign('problem_cause_id_category_foreign');
        });
    }
}
