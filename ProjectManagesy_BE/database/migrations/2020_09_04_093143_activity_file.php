<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ActivityFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_file', function (Blueprint $table){
            $table->bigIncrements('activity_file_id');
            $table->string('activity_file_name',45);
            $table->string('activity_file',100);
            $table->string('keep_file_name',100);
            $table->bigInteger('activity_id')->unsigned();

            $table->timestamps();

            $table->foreign('activity_id')->references('activity_id')->on('activity')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_file');

    }
}
