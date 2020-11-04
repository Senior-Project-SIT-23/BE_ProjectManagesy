<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DataActivityCollegeStudent extends Model
{
    protected $table = 'data_activity_college_student';
    protected $fillable = ['data_first_name', 'data_surname', 'data_major', 'data_year',
    'data_high_school_name', 'data_form_activity_name', 'data_gpax'];
    // public function activity_file()
    // {
    //     return $this->hasMany(ActivityFile::class,'activity_id');

    // }
}
