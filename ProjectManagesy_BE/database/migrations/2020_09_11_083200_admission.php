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
            $table->string('admission_name',100);//ชื่อโครงการ
            $table->string('round_name',100);//รอบที่สมัคร
            $table->string('admission_major',45);//คณะที่สมัคร
            $table->string('admission_year',45);//ปีการศึกษาที่เข้าข้อมูลเข้า


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
