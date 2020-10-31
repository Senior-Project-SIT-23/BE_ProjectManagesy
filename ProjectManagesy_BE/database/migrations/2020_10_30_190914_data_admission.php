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
        Schema::create('data_admission', function (Blueprint $table){
            $table->bigIncrements('data_admission_id')->unsigned();
            $table->string('data_first_name',100);
            $table->string('data_surname', 100);
            $table->string('data_school_name',100);
            $table->string('data_year',100);
            $table->string('data_major',100);
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
            Schema::dropIfExists('data_admission');
            Schema::enableForeignKeyConstraints();
        }
    }
}
