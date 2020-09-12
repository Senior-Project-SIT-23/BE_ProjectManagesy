<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AdmissionRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class AdmissionManagementController extends Controller
{
    private $admission;

    public function __construct(AdmissionRepositoryInterface $admission)

    {
        $this->admission = $admission;
    }

    public function storeAdmission(Request $request)
    {
        // $messages = [
        //     'required' => 'The :attribute field is required.',
        // ];

        // //ตรวจสอบข้อมูล
        // $validator =  Validator::make($request->all(), [
        //     'admission_name' => 'required',
        //     'round_name' => 'required',
        //     'activity_major' => 'required',
        //     'activity_year' => 'required'
        // ], $messages);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 500);
        // }

        $data = $request->all();
        $this->admission->createAdmission($data);

        return response()->json('สำเร็จ', 200);
    }

    // public function indexAllAdmission()
    // {
    //     $admission = $this->admission->getAllAdmission();
    //     return response()->json('สำเร็จ', 200);
    // }





}
