<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CollegeStudentRepositoryInterface;
use Illuminate\Support\Facades\Validator;



class CollegeStudentController extends Controller
{
    private $colleage_student;

    public function __construct(CollegeStudentRepositoryInterface $colleage_student)

    {
        $this->colleage_student = $colleage_student;
    }

    public function storeCollegeStudent(Request $request)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
        ];

        //ตรวจสอบข้อมูล
        $validator =  Validator::make($request->all(), [
            'entrance_year' => 'required',
            'college_student_file_name' => 'required',
            'college_student_file' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $data = $request->all();
        foreach ($data['college_student_file'] as $value) {
            $validator2 =  Validator::make($value, [
                'data_student_id' => 'required',
                'data_id' => 'required',
                'data_entrance_year' => 'required',
                'data_first_name' => 'required',
                'data_surname' => 'required',
                'data_gender' => 'required',
                'data_major' => 'required',
                'data_tel' => 'required',
                'data_email' => 'required',
                'data_province' => 'required',
                'data_education' => 'required',
                'data_school_name' => 'required',
                'data_admission' => 'required',
                'data_gpax' => 'required'
            ], $messages);
            if ($validator2->fails()) {
                return response()->json($validator2->errors(), 500);
            }
        }

        $status = $this->colleage_student->chcekStatus($data);

        if ($status == 'false_status') {
            return response()->json('Not Success, status error', 500);
        } else if ($status == 'false_admission_name') {
            return response()->json('Not Success, not match admission_name', 500);
        } else if ($status == 'true') {
            $college = $this->colleage_student->createCollegeStudent($data);
            if ($college == 'Fail') {
                return response()->json('Not Success, college student duplicate on file or do not have in admission', 500);
            } else {
                $this->colleage_student->updateStatus($data);
                return response()->json('สำเร็จ', 200);
            }
        }
    }

    public function editCollegeStudent(Request $request)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
        ];

        //ตรวจสอบข้อมูล
        $validator =  Validator::make($request->all(), [
            'college_student_id' => 'required',
            'entrance_year' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $data = $request->all();


        if ($data['college_student_file']) {
            foreach ($data['college_student_file'] as $value) {
                $validator2 =  Validator::make($value, [
                    'data_student_id' => 'required',
                    'data_id' => 'required',
                    'data_entrance_year' => 'required',
                    'data_first_name' => 'required',
                    'data_surname' => 'required',
                    'data_gender' => 'required',
                    'data_major' => 'required',
                    'data_tel' => 'required',
                    'data_email' => 'required',
                    'data_province' => 'required',
                    'data_education' => 'required',
                    'data_school_name' => 'required',
                    'data_admission' => 'required',
                    'data_gpax' => 'required'
                ], $messages);
                if ($validator2->fails()) {
                    return response()->json($validator2->errors(), 500);
                }
            }
            $status = $this->colleage_student->chcekStatus($data);

            if ($status == 'false_status') {
                return response()->json('Not Success, status error', 500);
            } else if ($status == 'false_admission_name') {
                return response()->json('Not Success, not match admission_name', 500);
            } else {
                $college = $this->colleage_student->editCollegeStudent($data);
                if ($college == 'Fail') {
                    return response()->json('Not Success, college student duplicate on file or do not have in admission', 500);
                } else {
                    $this->colleage_student->updateStatus($data);
                    return response()->json('สำเร็จ', 200);
                }
            }
        }



        $this->colleage_student->editCollegeStudent($data);
        return response()->json('สำเร็จ', 200);
    }

    public function deletetCollegeStudent(Request $request)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
        ];

        //ตรวจสอบข้อมูล
        $validator =  Validator::make($request->all(), [
            'college_student_id' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $data = $request->all();
        $this->colleage_student->deleteCollegeStudent($data);
        return response()->json('สำเร็จ', 200);
    }

    public function indexAllCollegeStudent()
    {
        $college = $this->colleage_student->getAllCollegeStudent();
        return response()->json($college, 200);
    }
}
