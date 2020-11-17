<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AdmissionRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ActivityImport;
use App\Imports\DataAdmissionImport;

class AdmissionManagementController extends Controller
{
    private $admission;

    public function __construct(AdmissionRepositoryInterface $admission)

    {
        $this->admission = $admission;
    }

    public function storeAdmission(Request $request)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
        ];

        //ตรวจสอบข้อมูล
        $validator =  Validator::make($request->all(), [
            'admission_name' => 'required',
            'round_name' => 'required',
            'admission_major' => 'required',
            'admission_year' => 'required',
            'admission_file_name' => 'required',
            'admission_file' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $data = $request->all();

        foreach ($data['admission_file'] as $value) {
            $validator2 =  Validator::make($value, [
                'data_first_name' => 'required',
                'data_surname' => 'required',
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

        $this->admission->createAdmission($data);
        $this->admission->createInformationStudentAdmission($data);

        return response()->json('สำเร็จ', 200);
    }

    public function indexAllAdmission()
    {
        $admission = $this->admission->getAllAdmission();
        return response()->json($admission, 200);
    }

    public function indexAdmission($admission_id)
    {
        $admission = $this->admission->getAdmissionById($admission_id);
        return response()->json($admission, 200);
    }

    public function editAdmission(Request $request)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
        ];

        //ตรวจสอบข้อมูล
        $validator =  Validator::make($request->all(), [
            'admission_id' => 'required',
            'admission_name' => 'required',
            'round_name' => 'required',
            'admission_major' => 'required',
            'admission_year' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $data = $request->all();

        if ($data['admission_file']) {
            foreach ($data['admission_file'] as $value) {
                $validator2 =  Validator::make($value, [
                    'data_first_name' => 'required',
                    'data_surname' => 'required',
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

        $this->admission->editAdmission($data);
        return response()->json('สำเร็จ', 200);
    }

    public function deleteAdmission(Request $request)
    {
        $data = $request->all();
        $this->admission->deleteAdmission($data);
        return response()->json('สำเร็จ', 200);
    }

    public function readFileAdmission($admission_id)
    {
        $admission = $this->admission->getAllFileAdmission($admission_id);
        return response()->json($admission, 200);
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
