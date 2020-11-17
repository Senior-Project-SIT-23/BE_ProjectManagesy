<?php

namespace App\Repositories;


use App\Model\ActivityStudent;
use App\Model\ActivityStudentFile;
use App\Model\DataAdmission;
use App\Model\DataStudentAdmission;
use App\Model\InformationStudent;

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

    public function getAllStudent()
    {
        $student = InformationStudent::all();

        return $student;
    }

    public function getStudent($data_first_name, $data_surname)
    {
        $student = InformationStudent::where('data_first_name', $data_first_name)
            ->where('data_surname', $data_surname)->first();

        $activity_file = ActivityStudentFile::where('data_first_name', $student->data_first_name)
            ->where('data_surname', $student->data_surname)->get();

        foreach ($activity_file as $value) {
            dd($value['activity_student_id']);
            $activity = ActivityStudent::where('activity_student_id', $value['activity_student_id'])->get();
        }

        $student->activity = $activity;

        return $student;
    }
}
