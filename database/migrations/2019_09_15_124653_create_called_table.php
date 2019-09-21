<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalledTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('called', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('caller_number');
            $table->unsignedBigInteger('id_establishment');
            $table->unsignedBigInteger('id_link');
            $table->integer('status'); // 1 - Fechado, 2 - Abertura Operadora, 3 - Técnico, 4 - InfraEstrutura, 5 - Falta de Energia, 6 - Retorno p.Fechamento, 7 - Cancelado
            $table->unsignedBigInteger('id_problem_cause');
            $table->integer('next_action');
            $table->unsignedBigInteger('id_user_open');
            $table->unsignedBigInteger('id_user_close')->nullable();
            $table->dateTime('hr_down');
            $table->dateTime('hr_up')->nullable();
            $table->time('downtime');
            $table->time('work_downtime');
            $table->string('massive_call_id')->nullable();
            $table->unsignedBigInteger('id_attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('called');
    }
}
