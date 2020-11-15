<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DataAdmission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_admission', function (Blueprint $table) {
            $table->bigIncrements('data_admission_id')->unsigned();
            $table->string('data_first_name', 100);
            $table->string('data_surname', 100);
            $table->string('data_school_name', 100);
            $table->string('data_year', 100);
            $table->string('data_major', 100);
            $table->string('data_gpax', 100);
            $table->string('data_email', 100);
            $table->string('data_tel', 10);
            $table->string('admission_name', 100);
            $table->string('round_name', 100);
            $table->bigInteger('admission_id')->unsigned()->nullable();
            $table->string('data_admission_file_name', 100)->nullable();
            $table->string('data_admission_file', 100)->nullable();
            $table->string('data_keep_file_name', 100)->nullable();

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
    { {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('data_admission');
            Schema::enableForeignKeyConstraints();
        }
    }
}
