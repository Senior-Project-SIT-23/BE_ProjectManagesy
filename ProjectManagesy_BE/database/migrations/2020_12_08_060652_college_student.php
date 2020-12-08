<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CollegeStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('college_student', function (Blueprint $table) {
            $table->bigIncrements('college_student_id')->unsigned();
            $table->string('college_student_name', 100);
            $table->string('entrance_year', 100);
            $table->string('college_student_file_name', 100);

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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('college_student');
        Schema::enableForeignKeyConstraints();
    }
}
