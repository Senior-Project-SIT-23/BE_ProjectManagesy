<?php

namespace App\Repositories;


use App\Model\Admission;
use App\Model\AdmissionFile;
use App\Model\DataAdmission;
use App\Model\InformationStudent;
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
                $admission_file = new AdmissionFile();
                $admission_file->data_first_name = $value['data_first_name'];
                $admission_file->data_surname = $value['data_surname'];
                $admission_file->data_school_name = $value['data_school_name'];
                $admission_file->data_programme = $value['data_programme'];
                $admission_file->data_gpax = $value['data_gpax'];
                $admission_file->data_email = $value['data_email'];
                $admission_file->data_tel = $value['data_tel'];
                $admission_file->admission_id = $admission->id;
                $admission_file->save();
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
        if ($data['admission_file']) {
            Admission::where('admission_id', $data['admission_id'])
                ->update([
                    'admission.admission_name' => $data['admission_name'],
                    'admission.round_name' => $data['round_name'],
                    'admission.admission_major' => $data['admission_major'],
                    'admission.admission_year' => $data['admission_year'],
                    'admission.admission_file_name' => $data['admission_file_name'],
                ]);

            //update ImformationStudent    
            $check_student = AdmissionFile::where('admission_id', $data['admission_id'])->get();
            foreach ($check_student as $value) {
                $num_of_admission = InformationStudent::where('data_first_name', $value['data_first_name'])
                    ->where('data_surname', $value['data_surname'])->first();
                $new_num_of_admission = $num_of_admission->num_of_admission - 1;
                InformationStudent::where('data_first_name', $value['data_first_name'])
                    ->where('data_surname', $value['data_surname'])->update(['num_of_admission' => $new_num_of_admission]);
                $check = InformationStudent::where('data_first_name', $value['data_first_name'])
                    ->where('data_surname', $value['data_surname'])->first();
                $check_activity = $check->num_of_activity;
                $check_admission  = $check->num_of_admission;
                if ($check_activity == 0 && $check_admission == 0) {
                    InformationStudent::where('data_first_name', $value['data_first_name'])
                        ->where('data_surname', $value['data_surname'])->delete();
                }
            }

            foreach ($data['admission_file'] as $value) {
                AdmissionFile::where('admission_id', $data['admission_id'])->delete();
                $admission_file = new AdmissionFile();
                $admission_file->data_first_name = $value['data_first_name'];
                $admission_file->data_surname = $value['data_surname'];
                $admission_file->data_school_name = $value['data_school_name'];
                $admission_file->data_programme = $value['data_programme'];
                $admission_file->data_gpax = $value['data_gpax'];
                $admission_file->data_email = $value['data_email'];
                $admission_file->data_tel = $value['data_tel'];
                $admission_file->admission_id = $data['admission_id'];
                $admission_file->save();

                $info_stu = new InformationStudent;
                $info_stu->data_first_name = $value['data_first_name'];
                $info_stu->data_surname = $value['data_surname'];
                $info_stu->data_degree = 'ม.6';
                $info_stu->data_email = $value['data_email'];
                $info_stu->data_school_name = $value['data_school_name'];
                $info_stu->data_tel = $value['data_tel'];
                $info_stu->num_of_activity = 0;
                $info_stu->num_of_admission = 1;
                $info_stu->save();
            }
        } else {
            Admission::where('admission_id', $data['admission_id'])->update([
                'admission.admission_name' => $data['admission_name'],
                'admission.round_name' => $data['round_name'],
                'admission.admission_major' => $data['admission_major'],
                'admission.admission_year' => $data['admission_year']
            ]);
        }
    }

    public function deleteAdmission($data)
    {
        foreach ($data['admission_id'] as $value) {

            $admission_file = AdmissionFile::where('admission_id', $value)->first();
            $data_first_name = $admission_file->data_first_name;
            $data_surname = $admission_file->data_surname;
            $info_stu = InformationStudent::where('data_first_name', $data_first_name)->where('data_surname', $data_surname)->first();
            $new_num_of_admission = $info_stu->num_of_admission - 1;
            InformationStudent::where('data_first_name', $data_first_name)->where('data_surname', $data_surname)
                ->update(['num_of_admission' => $new_num_of_admission]);
            $check = InformationStudent::where('data_first_name', $data_first_name)
                ->where('data_surname', $data_surname)->first();
            $check_activity = $check->num_of_activity;
            $check_admission  = $check->num_of_admission;
            if ($check_activity == 0 && $check_admission == 0) {
                InformationStudent::where('data_first_name', $data_first_name)
                    ->where('data_surname', $data_surname)->delete();
            }

            Admission::where('admission_id', $value)->delete();
            AdmissionFile::where('admission_id', $value)->delete();
        }
    }

    public function getAllFileAdmission($admission_id)
    {
        $admission = DataAdmission::where('admission_id', $admission_id)->get();
        return $admission;
    }

    public function createInformationStudentAdmission($data)
    {
        foreach ($data['admission_file'] as $value) {
            $check = InformationStudent::where('data_first_name', $value['data_first_name'])
                ->where('data_surname', $value['data_surname'])->first();
            if ($check) {
                $num_of_admission = $check->num_of_admission;
                $new_num_of_admission = $num_of_admission + 1;
                InformationStudent::where('data_first_name', $value['data_first_name'])
                    ->where('data_surname', $value['data_surname'])
                    ->update(['num_of_admission' => $new_num_of_admission]);
            } else {
                $info_stu = new InformationStudent;
                $info_stu->data_first_name = $value['data_first_name'];
                $info_stu->data_surname = $value['data_surname'];
                $info_stu->data_degree = 'ม.6';
                $info_stu->data_email = $value['data_email'];
                $info_stu->data_school_name = $value['data_school_name'];
                $info_stu->data_tel = $value['data_tel'];
                $info_stu->num_of_activity = 0;
                $info_stu->num_of_admission = 1;
                $info_stu->save();
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
