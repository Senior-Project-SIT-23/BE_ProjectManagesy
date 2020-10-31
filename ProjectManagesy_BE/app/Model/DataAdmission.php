<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DataAdmission extends Model
{
    protected $table= 'data_admission';
    protected $fillable = ['data_first_name', 'data_surname', 'data_school_name', 
    'data_year', 'data_major', 'data_gpax'];
    // public function activity_file()
    // {
    //     return $this->hasMany(ActivityFile::class,'activity_id');

    // }
}
