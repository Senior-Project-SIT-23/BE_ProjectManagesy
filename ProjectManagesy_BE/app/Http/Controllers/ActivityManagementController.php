<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ActivityRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ActivityImport;
use App\Imports\DataActivityImport;
use App\Imports\DataActivityStudentImport;

class ActivityManagementController extends Controller
{
    private $activity;

    public function __construct(ActivityRepositoryInterface $activity)

    {
        $this->activity = $activity;
    }

    public function storeStudentActivity(Request $request)
    {
        //เอาเข้ามูลจากfileเข้าDB
        //นร
        $import = Excel::import(new DataActivityStudentImport, $request->file('activity_file')->store('activity_student_csv'));
        $messages = [
            'required' => 'The :attribute field is required.',
        ];

        //ตรวจสอบข้อมูล
        $validator =  Validator::make($request->all(), [
            'activity_name' => 'required',
            'activity_year' => 'required',
            'activity_major' => 'required',
            'activity_file' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $data = $request->all();
        $this->activity->createActivity($data);

        return response()->json('สำเร็จ', 200);
    }

    public function indexStudentAllActivity()
    {
        $activity = $this->activity->getAllActivity();
        return response()->json($activity, 200);
    }

    public function indexStudentActivity($activity_id)
    {
        $activity = $this->activity->getActivityById($activity_id);
        return response()->json($activity, 200);
    }

    public function editStudentActivity(Request $request)
    {
        $data = $request->all();
        $this->activity->editActivity($data);
        return response()->json('สำเร็จ', 200);
    }

    public function deleteStudentActivity(Request $request)
    {
        $data = $request->all();
        $this->activity->deleteActivity($data);
        return response()->json('สำเร็จ', 200);
    }

    
}
