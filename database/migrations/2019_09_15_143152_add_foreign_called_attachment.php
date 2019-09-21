<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignCalledAttachment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('called', function (Blueprint $table) {
            $table->foreign('id_attachment')->references('id')->on('attachment');
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
            $table->dropForeign('called_id_attachment_foreign');
        });
    }
}
