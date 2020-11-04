<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ActivityStudentFile extends Model
{
    protected $table = 'activity_student_file';

    public function activity_student()
    {
        return $this->belongsTo(ActivityStudent::class, 'activity_student_id');
    }
}
