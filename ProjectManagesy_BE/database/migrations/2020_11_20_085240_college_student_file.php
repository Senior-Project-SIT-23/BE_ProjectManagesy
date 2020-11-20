<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CollegeStudentFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('college_student_file', function (Blueprint $table) {
            $table->string('data_student_id', 11)->primary();
            $table->string('data_id', 13);
            $table->string('data_entrance_year', 100);
            $table->string('data_first_name', 100);
            $table->string('data_surname', 100);
            $table->string('data_major', 100);
            $table->string('data_school_name', 100);
            $table->string('data_admission', 100);
            $table->string('data_gpax', 100);
            $table->bigInteger('college_student_id')->unsigned();
            $table->timestamps();

            $table->foreign('data_id')->references('id')->on('information_student')->onDelete('cascade');
            $table->foreign('college_student_id')->references('college_student_id')->on('college_student')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('college_student_file');
    }
}
