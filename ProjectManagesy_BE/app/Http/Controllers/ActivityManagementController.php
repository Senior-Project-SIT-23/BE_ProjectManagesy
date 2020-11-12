<?php

namespace App\Http\Controllers;

use App\Model\ActivityStudent;
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
        $activity_file = $this->activity->createActivity($data);

        $file_name =  $request->file('activity_file')->getClientOriginalName();
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_name_random = $file_name . "_" . $this->incrementalHash() . ".$extension";

        $import = Excel::import(new DataActivityStudentImport($activity_file->id, $file_name, $file_name_random), $request->file('activity_file')
            ->storeAs('activity_student_csv', "$file_name_random"));

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
        $messages = [
            'required' => 'The :attribute field is required.',
        ];

        //ตรวจสอบข้อมูล
        $validator =  Validator::make($request->all(), [
            'activity_id' => 'required',
            'activity_name' => 'required',
            'activity_year' => 'required',
            'activity_major' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $data = $request->all();
        $this->activity->editActivity($data);

        if ($request->file('new_activity_file')) {

            $file_name =  $request->file('new_activity_file')->getClientOriginalName();
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $file_name_random = $file_name . "_" . $this->incrementalHash() . ".$extension";

            $import = Excel::import(new DataActivityStudentImport($data['activity_id'], $file_name, $file_name_random), $request->file('new_activity_file')
                ->storeAs('activity_student_csv', "$file_name_random"));
        }


        return response()->json('สำเร็จ', 200);
    }

    public function deleteStudentActivity(Request $request)
    {
        $data = $request->all();
        $this->activity->deleteActivity($data);
        return response()->json('สำเร็จ', 200);
    }

    public function readFileStudentActivity($activity_id){
        $activity = $this->activity->getAllFileStudentActivity($activity_id);
        return response()->json($activity, 200);

    }

    //count
    public function countStudentAllActivity($activity_id){
        $activity = $this->activity->countActivity($activity_id);
        return response()->json($activity, 200);
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
