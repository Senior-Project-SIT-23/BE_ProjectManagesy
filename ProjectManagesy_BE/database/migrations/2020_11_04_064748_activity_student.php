<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ActivityStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_student', function (Blueprint $table){
            $table->bigIncrements('activity_student_id')->unsigned();
            $table->string('activity_student_name',45);
            $table->integer('activity_student_year');
            $table->string('activity_student_major',45);

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
        Schema::dropIfExists('activity_student');
        Schema::enableForeignKeyConstraints();
    }
}
