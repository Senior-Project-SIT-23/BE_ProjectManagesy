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
        //เอาเข้ามูลจากfileเข้าDB
        $import = Excel::import(new DataAdmissionImport, $request->file('admission_file')->store('temp'));
        $messages = [
            'required' => 'The :attribute field is required.',
        ];


        //ตรวจสอบข้อมูล
        $validator =  Validator::make($request->all(), [
            'admission_name' => 'required',
            'round_name' => 'required',
            'admission_major' => 'required',
            'admission_year' => 'required',
            'admission_file' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $data = $request->all();
        $this->admission->createAdmission($data);

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
        return response()->json('สำเร็จ', 200);
    }
    public function editAdmission(Request $request)
    {
        $data = $request->all();
        $this->admission->editAdmission($data);
        return response()->json('สำเร็จ', 200);
    }

    public function deleteAdmission(Request $request)
    {
        $data = $request->all();
        $this->admission->deleteAdmission($data);
        return response()->json('สำเร็จ', 200);
    }
    
}
