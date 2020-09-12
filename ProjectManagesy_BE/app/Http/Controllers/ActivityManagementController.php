<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ActivityRepositoryInterface;
use Illuminate\Support\Facades\Validator;


class ActivityManagementController extends Controller
{
    private $activity;

    public function __construct(ActivityRepositoryInterface $activity)

    {
        $this->activity = $activity;
    }

    public function storeActivity(Request $request)
    {

        $messages = [
            'required' => 'The :attribute field is required.',
        ];

        //ตรวจสอบข้อมูล
        $validator =  Validator::make($request->all(), [
            'activity_name' => 'required',
            'activity_year' => 'required',
            'activity_major' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $data = $request->all();
        $this->activity->createActivity($data);

        return response()->json('สำเร็จ', 200);
    }

    public function indexAllActivity()
    {
        $activity = $this->activity->getAllActivity();
        return response()->json($activity, 200);
    }

    public function indexActivity($activity_id)
    {
        $activity = $this->activity->getActivityById($activity_id);
        return response()->json($activity, 200);
    }

    public function editActivity(Request $request)
    {
        $data = $request->all();
        $this->activity->editActivity($data);
        return response()->json('สำเร็จ', 200);

    }

    public function deleteActivity(Request $request)
    {
        $data = $request->all();
        $this->activity->deleteActivity($data);
        return response()->json('สำเร็จ', 200);

        
    }
}
