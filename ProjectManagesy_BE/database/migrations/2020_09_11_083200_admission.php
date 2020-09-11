<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Admission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission', function (Blueprint $table){
            $table->bigIncrements('admission_id')->unsigned();
            $table->string('admission_name',45);
            $table->string('round_name',45);
            $table->string('admission_major',45);
            $table->string('admission_year',45);

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
        Schema::dropIfExists('admission');
        Schema::enableForeignKeyConstraints();
    }
}
