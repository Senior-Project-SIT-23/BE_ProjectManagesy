<?php

namespace App\Repositories;


use App\Model\Admission;
use App\Model\AdmissionFile;
use App\Model\DataAdmission;
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
        $admission->admission_file_name = $data['admission_file_name'];
        $admission->save();

        foreach ($data['admission_file_name'] as  $value) {
            if (
                $value['admission_file_name'] && $value['admission_file']
                && $value['keep_file_name']
            ) {

                $admission_file = new AdmissionFile();
                $admission_file->admission_file_name = $value['admission_file_name'];
                $admission_file->admission_file = $value['admission_file'];
                $admission_file->keep_file_name = $value['keep_file_name'];
                $admission_file->admission_id = $value['admission_id'];
                $admission->save();
            } else {
                AdmissionFile::where('admission_id', $admission->id)->delete();
            }
        }

        // $admission_id = $admission->id;

        // $temp_name = $data['admission_file']->getClientOriginalName(); //เอาชื่อไฟล์ที่เก็บอยุ่ในvalueมาเก็บไว้ในtempname
        // $name = pathinfo($temp_name, PATHINFO_FILENAME); //เก็บชื่อของไฟล์
        // $extension = pathinfo($temp_name, PATHINFO_EXTENSION); //เก็บนามสกุล
        // $custom_file_name = $name . "_" . $this->incrementalHash() . ".$extension";
        // $path = $data['admission_file']->storeAs('/admission', $custom_file_name); // เก็บไว้ในไฟล์โฟลเดอร์ของbackend, /admission คือโฟลเดอร์ที่จะเก็บ สร้างauto

        // $admission_file = new AdmissionFile();
        // $admission_file->admission_file_name = $temp_name;
        // $admission_file->admission_file = $path;
        // $admission_file->keep_file_name = $custom_file_name;
        // $admission_file->admission_id = $admission_id;
        // $admission_file->save();

        // return $admission_file;
    }

    public function getAllAdmission()
    {
        $admission = Admission::all();

        foreach ($admission as $value) {
            $admissionS_file = AdmissionFile::where('admission_id', $value['admission_id'])->get();
            $value['admission_file'] = $admissionS_file;
        }

        return $admission;
        // $admission = Admission::join('admission_file', 'admission_file.admission_id', '=', 'admission.admission_id')
        //     ->orderBy("admission.created_at", "asc")->get();
        // return $admission;
    }

    public function getAdmissionById($admission_id)
    {
        $admission = Admission::where('admission_id', $admission_id)->first();

        $attachment = AdmissionFile::where('admission_id', $admission_id)->get();

        $admission->attachment = $attachment;

        return $admission;
    }

    public function editAdmission($data)
    {
        $admission_old = Admission::where('admission_id', $data['admission_id'])->first();


        Admission::where('admission_id', $data['admission_id'])->update([
            'admission.admission_name' => $data['admission_name'],
            'admission.round_name' => $data['round_name'],
            'admission.admission_major' => $data['admission_major'],
            'admission.admission_year' => $data['admission_year']
        ]);

        if ($data['admission_file_name']) {
            Admission::where('admisson_id', $data['admisson_id'])
                ->update([
                    'admisson.activity_student_file_name' => $data['admission_file_name']
                ]);
            Admission::where('admisson_id', $data['admisson_id'])->delete();
            foreach ($data['admission_file'] as  $value) {
                if (
                    $value['data_first_name'] && $value['data_surname']
                    && $value['data_degree'] && $value['data_school_name']
                    && $value['data_email'] && $value['data_tel']
                ) {
                    $admission_file = new AdmissionFile();
                    $admission_file->admission_file_name = $value['admission_file_name'];
                    $admission_file->admission_file = $value['admission_file'];
                    $admission_file->keep_file_name = $value['keep_file_name'];
                    $admission_file->admission_id = $value['admission_id'];
                } else {
                    Admission::where('admisson_id', $data['admisson_id'])->update([
                        'admisson.admission_name' => $admission_old->admission_name,
                        'admisson.round_name' => $admission_old->round_name,
                        'admisson.admission_major' => $admission_old->admission_major,
                        'admisson.admission_year' => $admission_old->admission_year
                    ]);
                }
            }
        }



        // DataAdmission::where('admission_id', $data['admission_id'])
        //     ->update([
        //         'data_year' => $data['admission_year'],
        //         'admission_name' => $data['admission_name'],
        //         'data_major' => $data['admission_major'],
        //         'round_name' => $data['round_name']
        //     ]);

        // if ($data['delete_admission_file_id'] != "null") {
        //     $admission_file = AdmissionFile::where('admission_id', $data['admission_id'])->first();
        //     $data_admission = DataAdmission::where('admission_id', $data['admission_id'])->first();
        //     $keep_file_name = $admission_file->keep_file_name;
        //     $data_keep_file_name = $data_admission->data_keep_file_name;
        //     DataAdmission::where('admission_id', $data['admission_id'])->delete();
        //     unlink(storage_path('app/admission/' . $keep_file_name));
        //     unlink(storage_path('app/admission_csv/' . $data_keep_file_name));
        // }

        // if ($data['new_admission_file'] != "null") {
        //     $temp_name = $data['new_admission_file']->getClientOriginalName();
        //     $name = pathinfo($temp_name, PATHINFO_FILENAME);
        //     $extension = pathinfo($temp_name, PATHINFO_EXTENSION);
        //     $custom_file_name = $name . "_" . $this->incrementalHash() . ".$extension";
        //     $path = $data['new_admission_file']->storeAs('/admission', $custom_file_name);
        //     AdmissionFile::where('admission_id', $data['admission_id'])
        //         ->update([
        //             'admission_file.admission_file_name' => $temp_name,
        //             'admission_file.admission_file' => $path,
        //             'admission_file.keep_file_name' => $custom_file_name,
        //         ]);
        // }
    }

    public function deleteAdmission($data)
    {


        foreach ($data['admission_id'] as $value) {
            Admission::where('admission_id', $value)->delete();
            AdmissionFile::where('admission_id', $value)->delete();
        }
        // foreach ($data['admission_id'] as $value) {
        //     $adminssion_file = AdmissionFile::where('admission_id', $value)->first();
        //     $data_admission_file = DataAdmission::where('admission_id', $value)->first();

        //     Admission::where('admission_id', $value)->delete();
        //     AdmissionFile::where('admission_id', $value)->delete();
        //     DataAdmission::where('admission_id', $value)->delete();

        //     unlink(storage_path('app/admission/' . $adminssion_file->keep_file_name));
        //     unlink(storage_path('app/admission_csv/' . $data_admission_file->data_keep_file_name));
        // }
    }

    public function getAllFileAdmission($admission_id)
    {
        $admission = DataAdmission::where('admission_id', $admission_id)->get();
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
