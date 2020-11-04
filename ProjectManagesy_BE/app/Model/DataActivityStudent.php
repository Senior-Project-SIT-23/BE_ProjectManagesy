<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DataActivityStudent extends Model
{
    protected $table= 'data_activity_student';
    protected $fillable = ['data_first_name', 'data_surname', 'data_degree', 
    'data_school_name', 'data_from_activity_name', 'data_email', 'data_tel'];
    // public function activity_file()
    // {
    //     return $this->hasMany(ActivityFile::class,'activity_id');

    // }
}