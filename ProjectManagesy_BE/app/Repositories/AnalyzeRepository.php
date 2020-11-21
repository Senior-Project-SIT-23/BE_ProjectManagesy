<?php

namespace App\Repositories;

use App\Model\ActivityStudent;
use App\Model\ActivityStudentFile;
use App\Model\Admission;
use App\Model\AdmissionFile;
use App\Model\InformationStudent;
use App\Model\CollegeStudentFile;

class AnalyzeRepository implements AnalyzeRepositoryInterface
{
    public function getAnalyzeByYear($year)
    {

        $college_student_sit = CollegeStudentFile::where('data_entrance_year', $year)->get();
        $college_student_it = CollegeStudentFile::where('data_entrance_year', $year)
            ->where('data_major', 'IT')->get();
        $college_student_cs = CollegeStudentFile::where('data_entrance_year', $year)
            ->where('data_major', 'CS')->get();
        $college_student_dsi = CollegeStudentFile::where('data_entrance_year', $year)
            ->where('data_major', 'DSI')->get();
        $admission = Admission::where('admission_year', $year)
            ->join('admission_file', 'admission_file.admission_id', '=', 'admission.admission_id')->get();
        $activity_file = ActivityStudentFile::where('data_year', $year)->select('data_id')->distinct()->get();

        // $temp_admission_it = Admission::where('admission_year', $year)->where('admission_major', 'IT')->get();

        // foreach ($temp_admission_it as $value) {
        //     $admission_file = AdmissionFile::where('admission_id', $value['admission_id'])->get();
        //     $value['admission_file'] = $admission_file;
        //     foreach ($admission_file as $values) {
        //         $temp = array($values['data_school_name'] => null);
        //     }
        // }



        $data = array(
            "num_of_sit_student" => count($college_student_sit),
            "num_of_it_student" => count($college_student_it),
            "num_of_cs_student" => count($college_student_cs),
            "num_of_dsi_student" => count($college_student_dsi),
            "num_of_admission_student" => count($admission),
            "num_of_activity_student" => count($activity_file),
            // "school_admission"  => $temp,
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
