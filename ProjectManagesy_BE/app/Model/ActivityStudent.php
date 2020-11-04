<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ActivityStudent extends Model
{
    protected $table= 'activity_student';
    protected $fillable = ['activity_name', 'activity_major'];

    public function activity_student_file()
    {
        return $this->hasMany(ActivityStudentFile::class,'activity_student_id');

    }
}
