<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InformationStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('information_student', function (Blueprint $table) {
            $table->string('id',13)->primary();
            $table->string('data_first_name', 100);
            $table->string('data_surname', 100);
            $table->string('data_degree', 100);
            $table->string('data_email', 100);
            $table->string('data_school_name', 100);
            $table->string('data_tel', 100);
            $table->integer('num_of_activity')->nullable();
            $table->integer('num_of_admission')->nullable();

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
        Schema::dropIfExists('information_student');
        Schema::enableForeignKeyConstraints();
    }
}
