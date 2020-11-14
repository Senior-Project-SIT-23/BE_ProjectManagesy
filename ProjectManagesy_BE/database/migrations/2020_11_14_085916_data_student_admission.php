<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DataStudentAdmission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_student_admission', function (Blueprint $table) {
            $table->bigIncrements('data_student_admission_id')->unsigned();
            $table->string('data_first_name', 100);
            $table->string('data_surname', 100);
            $table->string('data_school_name', 100);
            $table->string('data_year', 100);
            $table->string('data_major', 100);
            $table->string('data_gpax', 100);
            $table->string('data_email', 100);
            $table->string('data_phone', 10);
            $table->integer('count_admission');
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
        Schema::dropIfExists('data_student_admission');
    }
}
