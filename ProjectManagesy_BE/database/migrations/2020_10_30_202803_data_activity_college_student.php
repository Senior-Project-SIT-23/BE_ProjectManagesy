<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DataActivityCollegeStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_activity_college_student', function (Blueprint $table){
            $table->bigIncrements('data_activity_id')->unsigned();
            $table->string('data_first_name',100);
            $table->string('data_surname', 100);
            $table->string('data_major',100);
            $table->string('data_year',100);
            $table->string('data_high_school_name',100);
            $table->string('data_form_activity_name',100);
            $table->string('data_gpax',100);
           

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
        {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('data_activity_college_student');
            Schema::enableForeignKeyConstraints();
        }
    }
}
