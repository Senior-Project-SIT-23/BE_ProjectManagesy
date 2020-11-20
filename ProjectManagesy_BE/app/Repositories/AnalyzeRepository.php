<?php

namespace App\Repositories;

use App\Model\ActivityStudent;
use App\Model\ActivityStudentFile;
use App\Model\AdmissionFile;
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
        foreach ($student as $value) {

            $activity_file = ActivityStudentFile::where('data_id', $value['id'])
                ->join('activity_student', 'activity_student.activity_student_id', '=', 'activity_student_file.activity_student_id')->get();
            $admission_file = AdmissionFile::where('data_id', $value['id'])
                ->join('admission', 'admission.admission_id', '=', 'admission_file.admission_id')->get();

            $value['activity'] = $activity_file;
            $value['admission'] = $admission_file;
        }

        return $student;
    }
}
