<?php

namespace App\Repositories;

use App\Model\AdmissionFile;
use App\Model\ActivityStudentFile;
use App\Model\CollegeStudent;
use App\Model\CollegeStudentFile;
use Throwable;
use Illuminate\Support\Str;

class CollegeStudentRepository implements CollegeStudentRepositoryInterface
{
    public function createCollegeStudent($data)
    {
        $college_student = new CollegeStudent;
        $college_student->college_student_name = 'นักศึกษาปีการศึกษา' . ' ' . $data['entrance_year'];
        $college_student->entrance_year = $data['entrance_year'];
        $college_student->college_student_file_name = $data['college_student_file_name'];
        $college_student->save();

        try {
            foreach ($data['college_student_file'] as $value) {
                $college_student_file = new CollegeStudentFile;
                $college_student_file->data_student_id = $value['data_student_id'];
                $college_student_file->data_id = $value['data_id'];
                $college_student_file->data_entrance_year = $value['data_entrance_year'];
                $college_student_file->data_first_name = $value['data_first_name'];
                $college_student_file->data_surname = $value['data_surname'];
                $college_student_file->data_gender = $value['data_gender'];
                $college_student_file->data_major = $value['data_major'];
                $college_student_file->data_tel = $value['data_tel'];
                $college_student_file->data_email = $value['data_email'];
                $college_student_file->data_province = $value['data_province'];
                $college_student_file->data_education = $value['data_education'];
                $college_student_file->data_school_name = $value['data_school_name'];
                $college_student_file->data_admission = $value['data_admission'];
                $college_student_file->data_gpax = $value['data_gpax'];
                $college_student_file->college_student_id = $college_student->id;
                $college_student_file->save();
            }
        } catch (Throwable $e) {
            $check_college_student = CollegeStudentFile::where('college_student_id', $college_student->id)->first();
            if ($check_college_student == null) {
                CollegeStudent::where('college_student_id', $college_student->id)->delete();
            }

            return 'Fail';
        }
    }

    public function editCollegeStudent($data)
    {
        if ($data['college_student_file']) {
            CollegeStudent::where('college_student_id', $data['college_student_id'])
                ->update([
                    'college_student.college_student_name' => 'นักศึกษาปีการศึกษา' . ' ' . $data['entrance_year'],
                    'college_student.entrance_year' => $data['entrance_year'],
                    'college_student.college_student_file_name' => $data['college_student_file_name'],
                ]);
            CollegeStudentFile::where('college_student_id', $data['college_student_id'])->delete();
            foreach ($data['college_student_file']  as $value) {
                $college_student_file = new  CollegeStudentFile;
                $college_student_file->data_student_id = $value['data_student_id'];
                $college_student_file->data_id = $value['data_id'];
                $college_student_file->data_entrance_year = $value['data_entrance_year'];
                $college_student_file->data_first_name = $value['data_first_name'];
                $college_student_file->data_surname = $value['data_surname'];
                $college_student_file->data_gender = $value['data_gender'];
                $college_student_file->data_major = $value['data_major'];
                $college_student_file->data_tel = $value['data_tel'];
                $college_student_file->data_email = $value['data_email'];
                $college_student_file->data_province = $value['data_province'];
                $college_student_file->data_education = $value['data_education'];
                $college_student_file->data_school_name = $value['data_school_name'];
                $college_student_file->data_admission = $value['data_admission'];
                $college_student_file->data_gpax = $value['data_gpax'];
                $college_student_file->college_student_id = $data['college_student_id'];
                $college_student_file->save();
            }
        } else {
            CollegeStudent::where('college_student_id', $data['college_student_id'])
                ->update([
                    'college_student.college_student_name' => 'นักศึกษาปีการศึกษา' . ' ' . $data['entrance_year'],
                    'college_student.entrance_year' => $data['entrance_year']
                ]);
        }
    }

    public function deleteCollegeStudent($data)
    {
        foreach ($data['college_student_id'] as $value) {
            CollegeStudent::where('college_student_id', $value)->delete();
            CollegeStudentFile::where('college_student_id', $value)->delete();
        }
    }

    public function getAllCollegeStudent()
    {
        $college_student = CollegeStudent::all();

        foreach ($college_student as $value) {
            $college_student_file = CollegeStudentFile::where('college_student_id', $value['college_student_id'])->get();
            $value['college_student_file'] = $college_student_file;

            foreach ($college_student_file as $values) {
                $activity = ActivityStudentFile::where('data_id', $values['data_id'])
                    ->join('activity_student', 'activity_student.activity_student_id', '=', 'activity_student_file.activity_student_id')->get();
                $values['num_of_activity'] = count($activity);
                $values['activity'] = $activity;
            }
        }
        return $college_student;
    }

    public function chcekStatus($data)
    {
        // Str::lower($test);
        foreach ($data['college_student_file'] as $value) {
            $admission = AdmissionFile::where('data_id', $value['data_id'])->where('admission_name', $value['data_admission'])->first();
            if ($admission->status != 5) {
                $status = 'false';
                return $status;
            }
        }
    }

    public function updateStatus($data)
    {
        foreach ($data['college_student_file'] as $value) {
            $admission = AdmissionFile::where('data_id', $value['data_id'])->where('admission_name', $value['data_admission'])
                ->update(['status' => 6]);
        }
    }
}
