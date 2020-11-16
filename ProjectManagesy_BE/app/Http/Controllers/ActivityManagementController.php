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
        $messages = [
            'required' => 'The :attribute field is required.',
        ];

        //ตรวจสอบข้อมูล
        $validator =  Validator::make($request->all(), [
            'activity_student_name' => 'required',
            'activity_student_year' => 'required',
            'activity_student_major' => 'required',
            'activity_student_file_name' => 'required',
            'activity_student_file' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $data = $request->all();

        foreach ($data['activity_student_file'] as $value) {
            $validator2 =  Validator::make($value, [
                'data_first_name' => 'required',
                'data_surname' => 'required',
                'data_degree' => 'required',
                'data_school_name' => 'required',
                'data_programme' => 'required',
                'data_gpax' => 'required',
                'data_email' => 'required',
                'data_tel' => 'required'
            ], $messages);
            if ($validator2->fails()) {
                return response()->json($validator2->errors(), 500);
            }
        }

        $this->activity->createActivity($data);

        return response()->json('สำเร็จ', 200);
    }

    public function editStudentActivity(Request $request)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
        ];

        //ตรวจสอบข้อมูล
        $validator =  Validator::make($request->all(), [
            'activity_student_id' => 'required',
            'activity_student_name' => 'required',
            'activity_student_year' => 'required',
            'activity_student_major' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $data = $request->all();

        if ($data['activity_student_file']) {
            foreach ($data['activity_student_file'] as $value) {
                $validator2 =  Validator::make($value, [
                    'data_first_name' => 'required',
                    'data_surname' => 'required',
                    'data_degree' => 'required',
                    'data_school_name' => 'required',
                    'data_programme' => 'required',
                    'data_gpax' => 'required',
                    'data_email' => 'required',
                    'data_tel' => 'required'
                ], $messages);
                if ($validator2->fails()) {
                    return response()->json($validator2->errors(), 500);
                }
            }
        }

        $this->activity->editActivity($data);
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

    public function deleteStudentActivity(Request $request)
    {
        $data = $request->all();
        $this->activity->deleteActivity($data);
        return response()->json('สำเร็จ', 200);
    }

    public function readFileStudentActivity($activity_id)
    {
        $activity = $this->activity->getAllFileStudentActivity($activity_id);
        return response()->json($activity, 200);
    }

    //count
    public function countStudentAllActivity($activity_id)
    {
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
