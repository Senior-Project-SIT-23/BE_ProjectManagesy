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
        Schema::create('college_student_file', function (Blueprint $table){
            $table->bigIncrements('college_student_file_id')->unsigned();
            $table->string('data_year',100);
            $table->string('student_id', 100);
            $table->string('data_id', 100);
            $table->string('data_first_name',100);
            $table->string('data_surname',100);
            $table->string('data_major',100);
            $table->string('data_school_name',100);
            $table->string('data_gpax',100);
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
        
        Schema::dropIfExists('college_student_file');
    }
    
}
