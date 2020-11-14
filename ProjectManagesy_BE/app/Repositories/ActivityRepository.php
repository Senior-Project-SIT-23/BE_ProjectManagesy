<?php

namespace App\Repositories;

use App\Model\ActivityStudent;
use App\Model\Activity;
use App\Model\ActivityStudentFile;
use Illuminate\Notifications\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataActivityStudentImport;
use App\Model\DataActivityStudent;

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
            $activity_student_file->data_email = $value['data_email'];
            $activity_student_file->data_phone = $value['data_phone'];
            $activity_student_file->activity_student_id = $activity->id;
            $activity_student_file->save();
        }
    }


    public function getAllActivity()
    {
        $activity = ActivityStudent::all();
        
        foreach ($activity as $value) {
            $activity_file = ActivityStudentFile::where('activity_student_id',$value['activity_student_id'])->get();
            $value['acitivty_file'] = $activity_file;
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

    public function editActivity($data)
    {
        ActivityStudent::where('activity_student_id', $data['activity_id'])->update([
            'activity_student.activity_student_name' => $data['activity_name'],
            'activity_student.activity_student_year' => $data['activity_year'],
            'activity_student.activity_student_major' => $data['activity_major']
        ]);


        if ($data['delete_activity_file_id'] != "null") {
            $activity_file = ActivityStudentFile::where('activity_student_id', $data['activity_id'])->first();
            $data_activity = DataActivityStudent::where('activity_student_id', $data['activity_id'])->first();
            $keep_student_file_name = $activity_file->keep_student_file_name;
            $keep_data_file_name = $data_activity->data_keep_file_name;
            DataActivityStudent::where('activity_student_id', $data['activity_id'])->delete();
            unlink(storage_path('app/activity_student/' . $keep_student_file_name));
            unlink(storage_path('app/activity_student_csv/' . $keep_data_file_name));
        }

        if ($data['new_activity_file'] != "null") {
            $temp_name = $data['new_activity_file']->getClientOriginalName();
            $name = pathinfo($temp_name, PATHINFO_FILENAME);
            $extension = pathinfo($temp_name, PATHINFO_EXTENSION);
            $custom_file_name = $name . "_" . $this->incrementalHash() . ".$extension";
            $path = $data['new_activity_file']->storeAs('/activity_student', $custom_file_name);
            ActivityStudentFile::where('activity_student_id', $data['activity_id'])
                ->update([
                    'activity_student_file.activity_student_file_name' => $temp_name,
                    'activity_student_file.activity_student_file' => $path,
                    'activity_student_file.keep_student_file_name' => $custom_file_name,
                ]);
        }
    }

    public function deleteActivity($data)
    {
        foreach ($data['activity_id'] as $value) {
            $activity_file = ActivityStudentFile::where('activity_student_id', $value)->first();
            $data_activity_file = DataActivityStudent::where('activity_student_id', $value)->first();

            ActivityStudent::where('activity_student_id', $value)->delete();
            ActivityStudentFile::where('activity_student_id', $value)->delete();
            DataActivityStudent::where('activity_student_id', $value)->delete();

            unlink(storage_path('app/activity_student/' . $activity_file->keep_student_file_name));
            unlink(storage_path('app/activity_student_csv/' . $data_activity_file->data_keep_file_name));
        }
    }


    public function getAllFileStudentActivity($activity_id)
    {
        $activity = DataActivityStudent::where('activity_student_id', $activity_id)->get();
        return $activity;
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
