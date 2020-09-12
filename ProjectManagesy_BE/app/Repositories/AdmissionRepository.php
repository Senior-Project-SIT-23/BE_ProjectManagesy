<?php

namespace App\Repositories;


use App\Model\Admission;
use App\Model\AdmissionFile;
use Illuminate\Notifications\Action;

class AdmissionRepository implements AdmissionRepositoryInterface
{
    public function createAdmission($data)
    {
        $admission = new Admission;
        $admission->admission_name = $data['admission_name'];
        $admission->round_name = $data['round_name'];
        $admission->admission_major = $data['admission_major'];
        $admission->admission_year = $data['admission_year'];
        $admission->save();

        foreach ($data['admission_file'] as $value) {
            if ($value) {   
                $temp_admission = Admission::where('admission_name', $data['admission_name'])
                    ->where('round_name', $data['round_name'])
                    ->where('admission_year', $data['admission_year'])
                    ->first();

                $admission_id = $temp_admission->admission_id;

                $temp_name = $value->getClientOriginalName(); //เอาชื่อไฟล์ที่เก็บอยุ่ในvalueมาเก็บไว้ในtempname
                $name = pathinfo($temp_name, PATHINFO_FILENAME); //เก็บชื่อของไฟล์
                $extension = pathinfo($temp_name, PATHINFO_EXTENSION); //เก็บนามสกุล
                $custom_file_name = $name . "_" . $this->incrementalHash() . ".$extension";
                $path = $value->storeAs('/admission', $custom_file_name); // เก็บไว้ในไฟล์โฟลเดอร์ของbackend, /admission คือโฟลเดอร์ที่จะเก็บ สร้างauto

                $admission_file = new AdmissionFile();
                $admission_file->admission_file_name = $temp_name;
                $admission_file->admission_file = $path;
                $admission_file->keep_file_name = $custom_file_name;
                $admission_file->admission_id = $admission_id;
                $admission_file->save();
            }
        }
    }

    public function getAllAdmission(){
        $admission = Admission::all();
        $admission = Admission::join('admission_file', 'admission_file.admission_id', '=', 'admission.admission_id')->orderBy("admission.created_at", "desc")->get();
        return $admission;
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
