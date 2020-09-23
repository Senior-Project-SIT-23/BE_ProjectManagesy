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
            $table->text('admission_file_name',100);
            $table->string('admission_file',100);
            $table->string('keep_file_name',100);
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
