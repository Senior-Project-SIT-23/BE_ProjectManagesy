<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DataActivityStudent extends Model
{
    protected $table = 'data_activity_student';
    protected $fillable = [
        'data_first_name', 'data_surname', 'data_degree',
        'data_school_name', 'data_email', 'data_tel', 'activity_student_id', 'data_activity_file_name', 'data_activity_file', 'data_keep_file_name'
    ];
    // public function activity_file()
    // {
    //     return $this->hasMany(ActivityFile::class,'activity_id');

    // }
}
