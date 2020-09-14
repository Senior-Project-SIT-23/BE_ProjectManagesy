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

    public function getAllAdmission()
    {
        $admission = Admission::all();
        $admission = Admission::Leftjoin('admission_file', 'admission_file.admission_id', '=', 'admission.admission_id')
            ->orderBy("admission.created_at", "desc")->get();
        return $admission;
    }

    public function getAdmissionById($admission_id)
    {
        $admission = Admission::where('admission_id', $admission_id)->first();

        $attachment = Admission::where('admission.admission_id', $admission_id)->join('admission_file', 'admission_file.admission_id', '=', 'admission.admission_id')->get();

        $admission->$attachment = $attachment;
        return $admission;
    }

    public function editAdmission($data)
    {
        Admission::where('admission_id', $data['admission_id'])->update([
            'admission.admission_name' => $data['admission_name'],
            'admission.round_name' => $data['round_name'],
            'admission.admission_major' => $data['admission_major'],
            'admission.admission_year' => $data['admission_year']
        ]);

        foreach ($data['delete_admission_file_id'] as $value) {
            $admission = AdmissionFile::where('admission_file_id', $value)->first();
            if ($admission) {
                $admission_name = $admission->keep_file_name;
                AdmissionFile::where('admission_file_id', $value)->delete();
                unlink(storage_path('app/admission/' . $admission_name));
            }
        }


        foreach ($data['new_admission_file'] as $value) {
            if ($value) {
                $temp_name = $value->getClientOriginalName();
                $name = pathinfo($temp_name, PATHINFO_FILENAME);
                $extension = pathinfo($temp_name, PATHINFO_EXTENSION);
                $custom_file_name = $name . "_" . $this->incrementalHash() . ".$extension";
                $path = $value->storeAs('/admission', $custom_file_name);
                $admission_file = new AdmissionFile();
                $admission_file->admission_file_name = $temp_name;
                $admission_file->admission_file = $path;
                $admission_file->keep_file_name = $custom_file_name;
                $admission_file->admission_id = $data['admission_id'];
                $admission_file->save();
            }
        }
    }

    public function deleteAdmission($data)
    {
        $admission_id = explode(',', $data['admission_id'][0]);

        foreach ($admission_id as $value) {
            $admission_file = AdmissionFile::where('admission_id', $value)->get();

            Admission::where('admission_id', $value)->delete();
            AdmissionFile::where('admission_id', $value)->delete();

            foreach ($admission_file as $value) {
                $file_name = $value->keep_file_name;
                unlink(storage_path('app/admission/' . $file_name));
            }
        }
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
