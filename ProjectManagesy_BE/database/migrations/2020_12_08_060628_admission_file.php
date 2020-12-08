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
        Schema::create('admission_file', function (Blueprint $table) {
            $table->bigIncrements('admission_file_id')->unsigned();
            $table->string('admission_categories', 100);
            $table->string('admission_name', 100);
            $table->string('admission_major', 100);
            $table->string('data_id', 100);
            $table->string('passport', 100);
            $table->string('data_first_name', 100);
            $table->string('data_surname', 100);
            $table->string('data_gender', 100);
            $table->string('data_school_name', 100);
            $table->string('data_province', 100);
            $table->string('data_education', 100);
            $table->string('data_programme', 100);
            $table->string('data_tel', 100);
            $table->string('data_email', 100);
            $table->string('data_gpax', 100);
            $table->bigInteger('admission_id')->unsigned();

            $table->timestamps();
            $table->foreign('data_id')->references('id')->on('information_student')->onDelete('cascade');
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
