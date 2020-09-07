<?php

namespace App\Repositories;


use App\Model\Activity;
use App\Model\ActivityFile;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function createActivity($data)
    {
        $activity = new Activity();
        $activity->activity_name = $data['activity_name'];
        $activity->activity_year = $data['activity_year'];
        $activity->activity_major = $data['activity_major'];
        $activity->save();

        foreach ($data['activity_file'] as $key => $value) {
            $temp_activity = Activity::where('activity_name', $data['activity_name'])->first();
            $activity_id = $temp_activity->activity_id;
            $activity_file = new ActivityFile();
            $temp_name = $value->getClientOriginalName();
            $extension = pathinfo($temp_name, PATHINFO_EXTENSION);
            $custom_file_name = $data['activity_name'] . "_" . "$key" . ".$extension";
            $path = $value->storeAs('/activity', $custom_file_name);
            $activity_file->activity_file_name = $custom_file_name;
            $activity_file->activity_file = $path;
            $activity_file->activity_id = $activity_id;
            $activity_file->save();
        }
    }

    public function getAllActivity()
    {
    }
}
