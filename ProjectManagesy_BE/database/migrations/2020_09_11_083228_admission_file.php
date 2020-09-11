<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdmissionFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_file', function (Blueprint $table){
            $table->bigIncrements('admission_file_id')->unsigned();
            $table->string('admission_file_name',45);
            $table->string('admission_file',45);
            $table->string('keep_file_name',45);
            $table->bigInteger('admission_id')->unsigned();

            $table->timestamps();
            $table->foreign('admission_id')->references('admission_id')->on('admission')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admission_file');
    }
}
