<?php

namespace App\Repositories;


use App\Model\ActivityStudent;
use App\Model\DataAdmission;
use App\Model\DataStudentAdmission;

class AnalyzeRepository implements AnalyzeRepositoryInterface
{
    public function numOfActivityAndAdmission($year)
    {

        $activity = ActivityStudent::where('activity_student_year', $year)->get();


        $data = array(
            "num_of_activity_in " . "$year" => count($activity),
            "activity" => $activity,
        );

        return $data;
    }

    public function getStudent()
    {
        $student = DataStudentAdmission::join('');

        return $student;
    }
}
