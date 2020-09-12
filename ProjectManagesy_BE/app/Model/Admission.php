<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $table= 'admission';

    public function admission_file()
    {
        return $this->hasMany(ActivityFile::class,'admission_id');



    }
}