<?php

namespace App\Repositories;


use App\Model\Activity;
use App\Model\ActivityFile;
use Illuminate\Notifications\Action;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function createActivity($data)
    {
        $activity = new Activity;
        $activity->activity_name = $data['activity_name'];
        $activity->activity_year = $data['activity_year'];
        $activity->activity_major = $data['activity_major'];
        $activity->save();

        foreach ($data['activity_file'] as $value) {
            if ($value) {
                $temp_activity = Activity::where('activity_name', $data['activity_name'])->first();
                $activity_id = $temp_activity->activity_id;

                $temp_name = $value->getClientOriginalName();
                $name = pathinfo($temp_name, PATHINFO_FILENAME);
                $extension = pathinfo($temp_name, PATHINFO_EXTENSION);
                $custom_file_name = $name . "_" . $this->incrementalHash() . ".$extension";
                $path = $value->storeAs('/activity', $custom_file_name);
                $activity_file = new ActivityFile();
                $activity_file->activity_file_name = $temp_name;
                $activity_file->activity_file = $path;
                $activity_file->keep_file_name = $custom_file_name;
                $activity_file->activity_id = $activity_id;
                $activity_file->save();
            }
        }
    }


    public function getAllActivity()
    {
        $activity = Activity::all();
        $activity = Activity::join('activity_file', 'activity_file.activity_id', '=', 'activity.activity_id')->orderBy("activity.created_at", "desc")->get();
        return $activity;
    }

    public function getActivityById($activity_id)
    {
        $activity = Activity::where('activity_id', $activity_id)->first();

        $attachment = Activity::where('activity.activity_id', $activity_id)
            ->join('activity_file', 'activity_file.activity_id', '=', 'activity.activity_id')->get();

        $activity->attachment = $attachment;

        return $activity;
    }

    public function editActivity($data)
    {
        Activity::where('activity_id', $data['activity_id'])->update([
            'activity.activity_name' => $data['activity_name'],
            'activity.activity_year' => $data['activity_year'],
            'activity.activity_major' => $data['activity_major']
        ]);
        
        foreach ($data['delete_activity_file_id'] as $value) {
            $activity = ActivityFile::where('activity_file_id', $value)->first();
            if ($activity) {
                $activity_name = $activity->keep_file_name;
                ActivityFile::where('activity_file_id', $value)->delete();
                unlink(storage_path('app/activity/' . $activity_name));
            }
        }

        foreach ($data['new_activity_file'] as $value) {
            if ($value) {
                $temp_name = $value->getClientOriginalName();
                $name = pathinfo($temp_name, PATHINFO_FILENAME);
                $extension = pathinfo($temp_name, PATHINFO_EXTENSION);
                $custom_file_name = $name . "_" . $this->incrementalHash() . ".$extension";
                $path = $value->storeAs('/activity', $custom_file_name);
                $activity_file = new ActivityFile();
                $activity_file->activity_file_name = $temp_name;
                $activity_file->activity_file = $path;
                $activity_file->keep_file_name = $custom_file_name;
                $activity_file->activity_id = $data['activity_id'];
                $activity_file->save();
            }
        }
    }

    public function deleteActivity($data)
    {
        foreach ($data['activity_id'] as $value) {
            $activity_file = ActivityFile::where('activity_id', $value)->get();
         
            Activity::where('activity_id', $value)->delete();
            ActivityFile::where('activity_id', $value)->delete();
          
            foreach ($activity_file as $value) {
                $file_name = $value->keep_file_name;
                unlink(storage_path('app/activity/' . $file_name));
            }
        }

        foreach ($data['delete_activity_file_id'] as $value) {
            $activity = ActivityFile::where('activity_file_id', $value)->first();
            if ($activity) {
                $activity_name = $activity->keep_file_name;
                ActivityFile::where('activity_file_id', $value)->delete();
                unlink(storage_path('app/activity/' . $activity_name));
            }
        }
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
