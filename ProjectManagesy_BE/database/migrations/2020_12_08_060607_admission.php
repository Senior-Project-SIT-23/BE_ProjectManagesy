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
    { {
            Schema::create('admission', function (Blueprint $table) {
                $table->bigIncrements('admission_id')->unsigned();
                $table->string('admission_name', 100); //ชื่อโครงการ
                $table->string('admission_major', 45); //คณะที่สมัคร
                $table->string('admission_year', 45); //ปีการศึกษาที่เข้าข้อมูลเข้า
                $table->string('admission_file_name', 45);
                $table->bigInteger('entrance_id')->unsigned();

                $table->timestamps();
                $table->foreign('entrance_id')->references('entrance_id')->on('entrance')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admission');;
    }
}
