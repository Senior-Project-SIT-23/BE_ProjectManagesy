<?php

namespace App\Repositories;

use App\Model\ActivityStudent;
use App\Model\Activity;
use App\Model\ActivityStudentFile;
use Illuminate\Notifications\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataActivityStudentImport;
use App\Model\DataActivityStudent;
use App\Model\InformationStudent;
use Symfony\Polyfill\Intl\Idn\Info;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function createActivity($data)
    {
        $activity = new ActivityStudent;
        $activity->activity_student_name = $data['activity_student_name'];
        $activity->activity_student_year = $data['activity_student_year'];
        $activity->activity_student_major = $data['activity_student_major'];
        $activity->activity_student_file_name = $data['activity_student_file_name'];
        $activity->save();

        foreach ($data['activity_student_file'] as  $value) {
            $activity_student_file = new ActivityStudentFile();
            $activity_student_file->data_first_name = $value['data_first_name'];
            $activity_student_file->data_surname = $value['data_surname'];
            $activity_student_file->data_degree = $value['data_degree'];
            $activity_student_file->data_school_name = $value['data_school_name'];
            $activity_student_file->data_programme = $value['data_programme'];
            $activity_student_file->data_gpax = $value['data_gpax'];
            $activity_student_file->data_email = $value['data_email'];
            $activity_student_file->data_tel = $value['data_tel'];
            $activity_student_file->activity_student_id = $activity->id;
            $activity_student_file->save();
        }
    }

    public function editActivity($data)
    {
        if ($data['activity_student_file']) {
            ActivityStudent::where('activity_student_id', $data['activity_student_id'])
                ->update([
                    'activity_student.activity_student_name' => $data['activity_student_name'],
                    'activity_student.activity_student_year' => $data['activity_student_year'],
                    'activity_student.activity_student_major' => $data['activity_student_major'],
                    'activity_student.activity_student_file_name' => $data['activity_student_file_name'],
                ]);

            //update ImformationStudent    
            $check_student = ActivityStudentFile::where('activity_student_id', $data['activity_student_id'])->get();
            foreach ($check_student as $value) {
                $num_of_activity = InformationStudent::where('data_first_name', $value['data_first_name'])
                    ->where('data_surname', $value['data_surname'])->first();
                $new_num_of_activity = $num_of_activity->num_of_activity - 1;
                InformationStudent::where('data_first_name', $value['data_first_name'])
                    ->where('data_surname', $value['data_surname'])->update(['num_of_activity' => $new_num_of_activity]);
                $check = InformationStudent::where('data_first_name', $value['data_first_name'])
                    ->where('data_surname', $value['data_surname'])->first();
                $check_activity = $check->num_of_activity;
                $check_admission  = $check->num_of_admission;
                if ($check_activity == 0 && $check_admission == 0) {
                    InformationStudent::where('data_first_name', $value['data_first_name'])
                        ->where('data_surname', $value['data_surname'])->delete();
                }
            }

            ActivityStudentFile::where('activity_student_id', $data['activity_student_id'])->delete();
            foreach ($data['activity_student_file'] as  $value) {
                $activity_student_file = new ActivityStudentFile();
                $activity_student_file->data_first_name = $value['data_first_name'];
                $activity_student_file->data_surname = $value['data_surname'];
                $activity_student_file->data_degree = $value['data_degree'];
                $activity_student_file->data_school_name = $value['data_school_name'];
                $activity_student_file->data_programme = $value['data_programme'];
                $activity_student_file->data_gpax = $value['data_gpax'];
                $activity_student_file->data_email = $value['data_email'];
                $activity_student_file->data_tel = $value['data_tel'];
                $activity_student_file->activity_student_id = $data['activity_student_id'];
                $activity_student_file->save();

                $info_stu = new InformationStudent;
                $info_stu->data_first_name = $value['data_first_name'];
                $info_stu->data_surname = $value['data_surname'];
                $info_stu->data_degree = $value['data_degree'];
                $info_stu->data_email = $value['data_email'];
                $info_stu->data_school_name = $value['data_school_name'];
                $info_stu->data_tel = $value['data_tel'];
                $info_stu->num_of_activity = 1;
                $info_stu->num_of_admission = 0;
                $info_stu->save();
            }
        } else {
            ActivityStudent::where('activity_student_id', $data['activity_student_id'])->update([
                'activity_student.activity_student_name' => $data['activity_student_name'],
                'activity_student.activity_student_year' => $data['activity_student_year'],
                'activity_student.activity_student_major' => $data['activity_student_major']
            ]);
        }
    }

    public function getAllActivity()
    {
        $activity = ActivityStudent::all();

        foreach ($activity as $value) {
            $activity_file = ActivityStudentFile::where('activity_student_id', $value['activity_student_id'])->get();
            $value['activity_file'] = $activity_file;
        }

        return $activity;
    }

    public function getActivityById($activity_id)
    {
        $activity = ActivityStudent::where('activity_student_id', $activity_id)->first();

        $attachment = ActivityStudentFile::where('activity_student_id', $activity_id)->get();

        $activity->attachment = $attachment;

        return $activity;
    }



    public function deleteActivity($data)
    {
        foreach ($data['activity_student_id'] as $value) {
            $activity_file = ActivityStudentFile::where('activity_student_id', $value)->first();
            $data_first_name = $activity_file->data_first_name;
            $data_surname = $activity_file->data_surname;
            $info_stu = InformationStudent::where('data_first_name', $data_first_name)->where('data_surname', $data_surname)->first();
            $new_num_of_activity = $info_stu->num_of_activity - 1;
            InformationStudent::where('data_first_name', $data_first_name)->where('data_surname', $data_surname)
                ->update(['num_of_activity' => $new_num_of_activity]);
            $check = InformationStudent::where('data_first_name', $data_first_name)
                ->where('data_surname', $data_surname)->first();
            $check_activity = $check->num_of_activity;
            $check_admission  = $check->num_of_admission;
            if ($check_activity == 0 && $check_admission == 0) {
                InformationStudent::where('data_first_name', $data_first_name)
                    ->where('data_surname', $data_surname)->delete();
            }

            ActivityStudent::where('activity_student_id', $value)->delete();
            ActivityStudentFile::where('activity_student_id', $value)->delete();
        }
    }


    public function getAllFileStudentActivity($activity_id)
    {
        $activity = DataActivityStudent::where('activity_student_id', $activity_id)->get();
        return $activity;
    }

    public function createInformationStudentActivity($data)
    {
        foreach ($data['activity_student_file'] as $value) {
            $check = InformationStudent::where('data_first_name', $value['data_first_name'])
                ->where('data_surname', $value['data_surname'])->first();
            if ($check) {
                $num_of_activity = $check->num_of_activity;
                $new_num_of_activity = $num_of_activity + 1;
                InformationStudent::where('data_first_name', $value['data_first_name'])
                    ->where('data_surname', $value['data_surname'])
                    ->update(['num_of_activity' => $new_num_of_activity]);
            } else {
                $info_stu = new InformationStudent;
                $info_stu->data_first_name = $value['data_first_name'];
                $info_stu->data_surname = $value['data_surname'];
                $info_stu->data_degree = $value['data_degree'];
                $info_stu->data_email = $value['data_email'];
                $info_stu->data_school_name = $value['data_school_name'];
                $info_stu->data_tel = $value['data_tel'];
                $info_stu->num_of_activity = 1;
                $info_stu->num_of_admission = 0;
                $info_stu->save();
            }
        }
    }

    //count
    public function countActivity($activity_id)
    {

        //     // $activity = ActivityStudent::join('activity_student_file', 'activity_student_file.activity_student_id', '=', 'activity_student.activity_student_id')
        //     // ->orderBy("activity_student.created_at", "asc")->get();



        //     return $activity;
    }






    public function incrementalHash($len = 5)
    {
        $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $base = strlen($charset);
        $result = '';

        $now = explode(' ', microtime())[1];
        while ($now >= $base) {
            $i = $now % $base;
            $result = $charset[$i] . $result;
            $now /= $base;
        }
        return substr($result, -5);
    }
}
