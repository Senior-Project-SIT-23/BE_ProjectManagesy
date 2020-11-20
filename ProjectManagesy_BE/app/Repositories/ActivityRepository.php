<?php

namespace App\Repositories;

use ActivityStudentFile as GlobalActivityStudentFile;
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
            $activity_student_file->data_id = $value['data_id'];
            $activity_student_file->data_first_name = $value['data_first_name'];
            $activity_student_file->data_surname = $value['data_surname'];
            $activity_student_file->data_degree = $value['data_degree'];
            $activity_student_file->data_school_name = $value['data_school_name'];
            $activity_student_file->data_programme = $value['data_programme'];
            $activity_student_file->data_gpax = $value['data_gpax'];
            $activity_student_file->data_email = $value['data_email'];
            $activity_student_file->data_tel = $value['data_tel'];
            $activity_student_file->data_year = $data['activity_student_year'];
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
            foreach ($data['activity_student_file'] as $value) {
                $check_student = InformationStudent::where('id', $value['data_id'])->first();
                ActivityStudentFile::where('activity_student_id', $data['activity_student_id'])->delete();
                if ($check_student) {
                    InformationStudent::where('id', $value['data_id'])
                        ->update([
                            'data_email' => $value['data_email'],
                            'data_school_name' => $value['data_school_name'],
                            'data_tel' => $value['data_tel']
                        ]);
                    $activity_student_file = new ActivityStudentFile();
                    $activity_student_file->data_id = $value['data_id'];
                    $activity_student_file->data_first_name = $value['data_first_name'];
                    $activity_student_file->data_surname = $value['data_surname'];
                    $activity_student_file->data_degree = $value['data_degree'];
                    $activity_student_file->data_school_name = $value['data_school_name'];
                    $activity_student_file->data_programme = $value['data_programme'];
                    $activity_student_file->data_gpax = $value['data_gpax'];
                    $activity_student_file->data_email = $value['data_email'];
                    $activity_student_file->data_tel = $value['data_tel'];
                    $activity_student_file->data_year = $data['activity_student_year'];
                    $activity_student_file->activity_student_id = $data['activity_student_id'];
                    $activity_student_file->save();
                } else {
                    $info_stu = new InformationStudent;
                    $info_stu->id = $value['data_id'];
                    $info_stu->data_first_name = $value['data_first_name'];
                    $info_stu->data_surname = $value['data_surname'];
                    $info_stu->data_degree = $value['data_degree'];
                    $info_stu->data_email = $value['data_email'];
                    $info_stu->data_school_name = $value['data_school_name'];
                    $info_stu->data_tel = $value['data_tel'];
                    $info_stu->num_of_activity = 1;
                    $info_stu->num_of_admission = 0;
                    $info_stu->save();

                    $activity_student_file = new ActivityStudentFile();
                    $activity_student_file->data_id = $value['data_id'];
                    $activity_student_file->data_first_name = $value['data_first_name'];
                    $activity_student_file->data_surname = $value['data_surname'];
                    $activity_student_file->data_degree = $value['data_degree'];
                    $activity_student_file->data_school_name = $value['data_school_name'];
                    $activity_student_file->data_programme = $value['data_programme'];
                    $activity_student_file->data_gpax = $value['data_gpax'];
                    $activity_student_file->data_email = $value['data_email'];
                    $activity_student_file->data_tel = $value['data_tel'];
                    $activity_student_file->data_year = $data['activity_student_year'];
                    $activity_student_file->activity_student_id = $data['activity_student_id'];
                    $activity_student_file->save();
                }
            }
            $information_student_all = InformationStudent::all();
            foreach ($information_student_all as $value) {
                $num_of_activity = count(ActivityStudentFile::where('data_id', $value['id'])->get());
                InformationStudent::where('id', $value['id'])->update([
                    'num_of_activity' => $num_of_activity
                ]);
                $infomation_stu = InformationStudent::where('id', $value['id'])->first();
                $check_activity = $infomation_stu->num_of_activity;
                $check_admission = $infomation_stu->num_of_admission;
                if ($check_activity == 0 && $check_admission == 0) {
                    InformationStudent::where('id', $value['id'])->delete();
                }
            }
        } else {
            ActivityStudent::where('activity_student_id', $data['activity_student_id'])
                ->update([
                    'activity_student.activity_student_name' => $data['activity_student_name'],
                    'activity_student.activity_student_year' => $data['activity_student_year'],
                    'activity_student.activity_student_major' => $data['activity_student_major']
                ]);
            ActivityStudentFile::where('activity_student_id', $data['activity_student_id'])
                ->update(['data_year' => $data['activity_student_year']]);
        }


        // if ($data['activity_student_file']) {
        //     ActivityStudent::where('activity_student_id', $data['activity_student_id'])
        //         ->update([
        //             'activity_student.activity_student_name' => $data['activity_student_name'],
        //             'activity_student.activity_student_year' => $data['activity_student_year'],
        //             'activity_student.activity_student_major' => $data['activity_student_major'],
        //             'activity_student.activity_student_file_name' => $data['activity_student_file_name'],
        //         ]);

        //     //update ImformationStudent    
        //     $check_student = ActivityStudentFile::where('activity_student_id', $data['activity_student_id'])->get();
        //     foreach ($check_student as $value) {
        //         $num_of_activity = InformationStudent::where('id', $value['data_id'])->first();
        //         $new_num_of_activity = $num_of_activity->num_of_activity - 1;
        //         InformationStudent::where('id', $value['data_id'])
        //             ->update([
        //                 'num_of_activity' => $new_num_of_activity,
        //                 'data_email' => $value['data_email'],
        //                 'data_school_name' => $value['data_school_name'],
        //                 'data_tel' => $value['data_tel']
        //             ]);
        //         $check = InformationStudent::where('id', $value['data_id'])->first();
        //         $check_activity = $check->num_of_activity;
        //         $check_admission  = $check->num_of_admission;
        //         if ($check_activity == 0 && $check_admission == 0) {
        //             InformationStudent::where('id', $value['data_id'])->delete();
        //         }
        //     }

        //     ActivityStudentFile::where('activity_student_id', $data['activity_student_id'])->delete();
        //     foreach ($data['activity_student_file'] as  $value) {
        //         $info_stu = new InformationStudent;
        //         $info_stu->id = $value['data_id'];
        //         $info_stu->data_first_name = $value['data_first_name'];
        //         $info_stu->data_surname = $value['data_surname'];
        //         $info_stu->data_degree = $value['data_degree'];
        //         $info_stu->data_email = $value['data_email'];
        //         $info_stu->data_school_name = $value['data_school_name'];
        //         $info_stu->data_tel = $value['data_tel'];
        //         $info_stu->num_of_activity = 1;
        //         $info_stu->num_of_admission = 0;
        //         $info_stu->save();

        //         $activity_student_file = new ActivityStudentFile();
        //         $activity_student_file->data_id = $value['data_id'];
        //         $activity_student_file->data_first_name = $value['data_first_name'];
        //         $activity_student_file->data_surname = $value['data_surname'];
        //         $activity_student_file->data_degree = $value['data_degree'];
        //         $activity_student_file->data_school_name = $value['data_school_name'];
        //         $activity_student_file->data_programme = $value['data_programme'];
        //         $activity_student_file->data_gpax = $value['data_gpax'];
        //         $activity_student_file->data_email = $value['data_email'];
        //         $activity_student_file->data_tel = $value['data_tel'];
        //         $activity_student_file->activity_student_id = $data['activity_student_id'];
        //         $activity_student_file->save();
        //     }
        // } else {
        //     ActivityStudent::where('activity_student_id', $data['activity_student_id'])
        //         ->update([
        //             'activity_student.activity_student_name' => $data['activity_student_name'],
        //             'activity_student.activity_student_year' => $data['activity_student_year'],
        //             'activity_student.activity_student_major' => $data['activity_student_major']
        //         ]);
        // }
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

    public function getAllActivityNameList()
    {
        $information_student = InformationStudent::all();

        foreach ($information_student as $value) {
            if ($value['num_of_activity'] != 0) {
                $data[] = $value;
            }
        }

        $sorted = collect($data)->sortBy('data_first_name');
        $activity = $sorted->values()->all();
        return $activity;
    }

    public function deleteActivity($data)
    {
        foreach ($data['activity_student_id'] as $value) {
            ActivityStudent::where('activity_student_id', $value)->delete();
            ActivityStudentFile::where('activity_student_id', $value)->delete();

            $information_student_all = InformationStudent::all();
            foreach ($information_student_all as $value) {
                $num_of_activity = count(ActivityStudentFile::where('data_id', $value['id'])->get());
                InformationStudent::where('id', $value['id'])->update([
                    'num_of_activity' => $num_of_activity
                ]);
                $infomation_stu = InformationStudent::where('id', $value['id'])->first();
                $check_activity = $infomation_stu->num_of_activity;
                $check_admission = $infomation_stu->num_of_admission;
                if ($check_activity == 0 && $check_admission == 0) {
                    InformationStudent::where('id', $value['id'])->delete();
                }
            }
        }
    }

    public function createInformationStudentActivity($data)
    {

        foreach ($data['activity_student_file'] as $value) {
            $check = InformationStudent::where('id', $value['data_id'])->first();
            if ($check) {
                $num_of_activity = $check->num_of_activity;
                $new_num_of_activity = $num_of_activity + 1;
                InformationStudent::where('id', $value['data_id'])
                    ->update(['num_of_activity' => $new_num_of_activity]);
            } else {
                $info_stu = new InformationStudent;
                $info_stu->id = $value['data_id'];
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
