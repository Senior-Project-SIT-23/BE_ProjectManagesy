<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DataActivityStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_activity_student', function (Blueprint $table){
            $table->bigIncrements('data_activity_id')->unsigned();
            $table->string('data_first_name',100);
            $table->string('data_surname', 100);
            $table->string('data_degree',100);
            $table->string('data_school_name',100);
            $table->string('data_email',100);
            $table->string('data_tel',45);
            $table->bigInteger('activity_student_id')->unsigned()->nullable();
            $table->string('data_activity_file_name',100)->nullable();
            $table->string('data_activity_file',100)->nullable();
            $table->string('data_keep_file_name',100)->nullable();
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
        {
            Schema::dropIfExists('data_activity_student');
        }
    }
}
