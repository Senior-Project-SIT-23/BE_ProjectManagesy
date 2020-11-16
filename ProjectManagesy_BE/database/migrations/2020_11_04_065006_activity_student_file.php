<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ActivityStudentFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_student_file', function (Blueprint $table) {
            $table->bigIncrements('activity_student_file_id');
            $table->string('data_first_name', 100);
            $table->string('data_surname', 100);
            $table->string('data_degree', 100);
            $table->string('data_school_name', 100);
            $table->string('data_programme', 100);
            $table->string('data_gpax', 100);
            $table->string('data_email', 100);
            $table->string('data_tel', 100);
            $table->bigInteger('activity_student_id')->unsigned();

            $table->timestamps();

            $table->foreign('activity_student_id')->references('activity_student_id')->on('activity_student')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_student_file');
    }
}
