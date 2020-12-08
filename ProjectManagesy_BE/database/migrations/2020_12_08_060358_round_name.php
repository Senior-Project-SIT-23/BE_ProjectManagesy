<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Roundname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('round_name', function (Blueprint $table) {
            $table->bigIncrements('round_id')->unsigned();
            $table->string('round_name', 100);
            $table->bigInteger('entrance_id')->unsigned();

            $table->timestamps();
            $table->foreign('entrance_id')->references('entrance_id')->on('entrance')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roundname');
    }
}
