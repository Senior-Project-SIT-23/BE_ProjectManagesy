<?php

namespace App\Repositories;

use App\Model\ActivityStudent;
use App\Model\Activity;
use App\Model\ActivityStudentFile;
use Illuminate\Notifications\Action;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function createActivity($data)
    {
        $activity = new ActivityStudent;
        $activity->activity_student_name = $data['activity_name'];
        $activity->activity_student_year = $data['activity_year'];
        $activity->activity_student_major = $data['activity_major'];
        $activity->save();

        $temp_activity = ActivityStudent::where('activity_student_name', $data['activity_name'])
            ->where('activity_student_year', $data['activity_year'])
            ->where('activity_student_major', $data['activity_major'])->first();
        $activity_student_id = $temp_activity->activity_student_id;

        //บันทึกลง Activity file & เก็บไฟล์เข้าโฟลเดอร์ BE
        $temp_name = $data['activity_file']->getClientOriginalName();
        $name = pathinfo($temp_name, PATHINFO_FILENAME);
        $extension = pathinfo($temp_name, PATHINFO_EXTENSION);
        $custom_file_name = $name . "_" . $this->incrementalHash() . ".$extension";
        $path = $data['activity_file']->storeAs('/activity_student', $custom_file_name);
        $activity_file = new ActivityStudentFile();
        $activity_file->activity_student_file_name = $temp_name;
        $activity_file->activity_student_file = $path;
        $activity_file->keep_student_file_name = $custom_file_name;
        $activity_file->activity_student_id = $activity_student_id;
        $activity_file->save();
    }


    public function getAllActivity()
    {
        
        // $activity = Activity::join('activity_file', 'activity_file.activity_id', '=', 'activity.activity_id')->orderBy("activity.created_at", "asc")->get();

        

        // return $activity;
    }

    public function getActivityById($activity_id)
    {
        // $activity = Activity::where('activity_id', $activity_id)->first();

        // $attachment = Activity::where('activity.activity_id', $activity_id)
        //     ->join('activity_file', 'activity_file.activity_id', '=', 'activity.activity_id')->get();

        // $activity->attachment = $attachment;

        // return $activity;
    }

    public function editActivity($data)
    {
        // Activity::where('activity_id', $data['activity_id'])->update([
        //     'activity.activity_name' => $data['activity_name'],
        //     'activity.activity_year' => $data['activity_year'],
        //     'activity.activity_major' => $data['activity_major']
        // ]);

        // if ($data['new_activity_file']) {
        //     //Delete file in storage
        //     $activity_file = ActivityFile::where('activity_id', $data['activity_id'])->first();
        //     $keep_file_name = $activity_file->keep_file_name;
        //     unlink(storage_path('app/activity/' . $keep_file_name));
        //     //Create file in storage
        //     $temp_name = $data['new_activity_file']->getClientOriginalName();
        //     $name = pathinfo($temp_name, PATHINFO_FILENAME);
        //     $extension = pathinfo($temp_name, PATHINFO_EXTENSION);
        //     $custom_file_name = $name . "_" . $this->incrementalHash() . ".$extension";
        //     $path = $data['new_activity_file']->storeAs('/activity', $custom_file_name);
        //     //Update Activity File in DB
        //     ActivityFile::where('activity_id', $data['activity_id'])
        //         ->update([
        //             'activity_file_name' => $temp_name,
        //             'activity_file' => $path,
        //             'keep_file_name' => $custom_file_name
        //         ]);
        // }
    }

    public function deleteActivity($data)
    {
        //--Pea--
        // $activity_id = explode(',', $data['activity_id'][0]);
        // foreach ($activity_id as $value) {
        //     $activity_file = ActivityFile::where('activity_id', $value)->get();

        //     Activity::where('activity_id', $value)->delete();
        //     ActivityFile::where('activity_id', $value)->delete();

        //     foreach ($activity_file as $value) {
        //         $file_name = $value->keep_file_name;
        //         unlink(storage_path('app/activity/' . $file_name));
        //     }
        // }

        //-----------------------------Bom
        // foreach ($data['activity_id'] as $value) {
        //     $activity_file = ActivityFile::where('activity_id', $value)->get();
        //     Activity::where('activity_id', $value)->delete();
        //     ActivityFile::where('activity_id', $value)->delete();
        //     foreach ($activity_file as $value) {
        //         $file_name = $value->keep_file_name;
        //         unlink(storage_path('app/activity/' . $file_name));
        //     }
        // }
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
