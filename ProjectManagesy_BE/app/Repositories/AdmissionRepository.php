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

        if ($data['admission_file']) {
            foreach ($data['admission_file'] as  $value) {
                try {
                    if (
                        $value['data_first_name'] && $value['data_surname']
                        && $value['data_school_name'] && $value['data_gpax']
                        && $value['data_email'] && $value['data_tel']
                    ) {
                        $admission_file = new AdmissionFile();
                        $admission_file->data_first_name = $value['data_first_name'];
                        $admission_file->data_surname = $value['data_surname'];
                        $admission_file->data_school_name = $value['data_school_name'];
                        $admission_file->data_gpax = $value['data_gpax'];
                        $admission_file->data_email = $value['data_email'];
                        $admission_file->data_tel = $value['data_tel'];
                        $admission_file->admission_id = $admission->id;
                        $admission_file->save();
                    }
                } catch (\Throwable $th) {
                    Admission::where('admission_id', $admission->id)->delete();
                    return $th;
                }
            }
        }
    }

    public function getAllAdmission()
    {
        $admission = Admission::all();

        foreach ($admission as $value) {
            $admissionS_file = AdmissionFile::where('admission_id', $value['admission_id'])->get();
            $value['admission_file'] = $admissionS_file;
        }

        return $admission;
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
            Admission::where('admission_id', $data['admission_id'])
                ->update([
                    'admission.admission_file_name' => $data['admission_file_name']
                ]);
            foreach ($data['admission_file'] as $value) {
                try {
                    if (
                        $value['data_first_name'] && $value['data_surname']
                        && $value['data_school_name'] && $value['data_gpax']
                        && $value['data_email'] && $value['data_tel']
                    ) {
                        AdmissionFile::where('admission_id', $data['admission_id'])->delete();
                        $admission_file = new AdmissionFile();
                        $admission_file->data_first_name = $value['data_first_name'];
                        $admission_file->data_surname = $value['data_surname'];
                        $admission_file->data_school_name = $value['data_school_name'];
                        $admission_file->data_gpax = $value['data_gpax'];
                        $admission_file->data_email = $value['data_email'];
                        $admission_file->data_tel = $value['data_tel'];
                        $admission_file->admission_id = $data['admission_id'];
                        $admission_file->save();
                    }
                } catch (\Throwable $th) {
                    Admission::where('admission_id', $data['admission_id'])->update([
                        'admission.admission_name' => $admission_old->admission_name,
                        'admission.round_name' => $admission_old->round_name,
                        'admission.admission_major' => $admission_old->admission_major,
                        'admission.admission_year' => $admission_old->admission_year,
                        'admission.admission_file_name' => $admission_old->admission_file_name
                    ]);
                    return $th;
                }
            }
        }
    }

    public function deleteAdmission($data)
    {
        foreach ($data['admission_id'] as $value) {
            Admission::where('admission_id', $value)->delete();
            AdmissionFile::where('admission_id', $value)->delete();
        }
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
