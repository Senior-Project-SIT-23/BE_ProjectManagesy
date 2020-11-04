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
            $table->string('data_from_activity_name',100);
            $table->string('data_email',100);
            $table->string('data_tel',45);



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
            Schema::dropIfExists('data_activity_student');
            Schema::enableForeignKeyConstraints();
        }
    }
}
