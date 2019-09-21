<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstablishmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('establishment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('establishment_code');
            $table->unsignedBigInteger('bussiness_code')->nullable();
            $table->string('address');
            $table->string('neighborhood');
            $table->string('city');
            $table->string('state', 3);
            $table->string('document_establishment')->nullable();
            $table->string('document_establishment_alternate')->nullable();
            $table->string('phone_establishment')->nullable();
            $table->string('branch_establishment')->nullable();
            $table->string('opening_hours')->nullable();
            $table->string('manager_name', 191);
            $table->string('manager_contact');
            $table->unsignedBigInteger('regional_manager_code');
            $table->unsignedBigInteger('technician_code');
            $table->dateTime('holiday')->nullable();
            $table->enum('establishment_status', ['open', 'close']);
            $table->unsignedBigInteger('id_user');
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
        Schema::dropIfExists('establishment');
    }
}
