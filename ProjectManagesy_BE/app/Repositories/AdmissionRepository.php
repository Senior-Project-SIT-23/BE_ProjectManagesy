<?php

namespace App\Repositories;


use App\Model\Admission;
use App\Model\AdmissionFile;
use App\Model\CollegeStudent;
use App\Model\CollegeStudentFile;
use App\Model\DataAdmission;
use App\Model\InformationStudent;
use App\Model\Entrance;
use App\Model\RoundName;
use App\Model\Program;

class AdmissionRepository implements AdmissionRepositoryInterface
{
    public function createAdmission($data)
    {
        $admission = new Admission;
        $admission->admission_major = $data['admission_major'];
        $admission->admission_year = $data['admission_year'];
        $admission->admission_file_name = $data['admission_file_name'];
        $admission->entrance_id = $data['entrance_id'];
        $admission->round_id = $data['round_id'];
        $admission->program_id = $data['program_id'];
        $admission->save();

        if ($data['admission_file']) {
            foreach ($data['admission_file'] as  $value) {
                $admission_file = new AdmissionFile();
                $admission_file->admission_categories = $value['admission_categories'];
                $admission_file->admission_name = $value['admission_name'];
                $admission_file->data_year = $value['data_year'];
                $admission_file->admission_major = $value['admission_major'];
                $admission_file->data_id = $value['data_id'];
                $admission_file->data_first_name = $value['data_first_name'];
                $admission_file->data_surname = $value['data_surname'];
                $admission_file->data_gender = $value['data_gender'];
                $admission_file->data_school_name = $value['data_school_name'];
                $admission_file->data_province = $value['data_province'];
                $admission_file->data_education = $value['data_education'];
                $admission_file->data_programme = $value['data_programme'];
                $admission_file->data_tel = $value['data_tel'];
                $admission_file->data_email = $value['data_email'];
                $admission_file->data_gpax = $value['data_gpax'];
                $admission_file->status = 1;
                $admission_file->admission_id = $admission->id;
                $admission_file->save();
            }
        }
    }

    public function getAllAdmission()
    {
        $admission = Admission::select('admission_id', 'admission_major', 'admission_year', 'admission_file_name')->get();

        foreach ($admission as $value) {
            $temp_admission = Admission::where('admission_id', $value['admission_id'])->first();
            $program_id = $temp_admission->program_id;

            $entrance = Program::select('admission.admission_id', 'entrance.entrance_id', 'entrance.entrance_name', 'round_name.round_id', 'round_name.round_name', 'program.program_id', 'program.program_name')
                ->where('program.program_id', $program_id)
                ->join('round_name', 'round_name.round_id', '=', 'program.round_id')
                ->join('entrance', 'entrance.entrance_id', '=', 'round_name.entrance_id')
                ->join('admission', 'admission.entrance_id', '=', 'entrance.entrance_id')
                ->where('admission.admission_id', $value['admission_id'])
                ->get();

            $value['entrance'] = $entrance;
            $admission_file = AdmissionFile::where('admission_id', $value['admission_id'])->get();
            $value['admission_file'] = $admission_file;
        }

        return $admission;
    }

    public function getAdmissionById($admission_id)
    {
        $temp_admission = Admission::where('admission_id', $admission_id)->first();
        $entrance_id = $temp_admission->entrance_id;
        $round_id = $temp_admission->round_id;
        $program_id = $temp_admission->program_id;

        $entrance = Entrance::select('entrance.entrance_id', 'entrance.entrance_name', 'round_name.round_id', 'round_name.round_name', 'program.program_id', 'program.program_name')
            ->where('entrance.entrance_id', $entrance_id)
            ->join('round_name', 'round_name.entrance_id', '=', 'entrance.entrance_id')->where('round_name.round_id', $round_id)
            ->join('program', 'program.round_id', '=', 'program_id')->where('program.program_id', $program_id)->get();

        $admission = Admission::select('admission_id', 'admission_major', 'admission_year', 'admission_file_name')
            ->where('admission_id', $admission_id)->first();

        $attachment = AdmissionFile::where('admission_id', $admission_id)->get();

        $admission->entrance = $entrance;
        $admission->attachment = $attachment;


        return $admission;
    }

    public function editAdmission($data)
    {
        if ($data['admission_file']) {
            Admission::where('admission_id', $data['admission_id'])
                ->update([
                    'admission.admission_major' => $data['admission_major'],
                    'admission.admission_year' => $data['admission_year'],
                    'admission.entrance_id' => $data['entrance_id'],
                    'admission.round_id' => $data['round_id'],
                    'admission.program_id' => $data['program_id'],
                    'admission.admission_file_name' => $data['admission_file_name'],
                ]);
            AdmissionFile::where('admission_id', $data['admission_id'])->delete();
            foreach ($data['admission_file'] as $value) {
                $check_student = InformationStudent::where('id', $value['data_id'])->first();
                if ($check_student) {
                    InformationStudent::where('id', $value['data_id'])
                        ->update([
                            'data_email' => $value['data_email'],
                            'data_school_name' => $value['data_school_name'],
                            'data_tel' => $value['data_tel']
                        ]);
                    $admission_file = new AdmissionFile();
                    $admission_file->admission_categories = $value['admission_categories'];
                    $admission_file->admission_name = $value['admission_name'];
                    $admission_file->data_year = $value['data_year'];
                    $admission_file->admission_major = $value['admission_major'];
                    $admission_file->data_id = $value['data_id'];
                    $admission_file->data_first_name = $value['data_first_name'];
                    $admission_file->data_surname = $value['data_surname'];
                    $admission_file->data_gender = $value['data_gender'];
                    $admission_file->data_school_name = $value['data_school_name'];
                    $admission_file->data_province = $value['data_province'];
                    $admission_file->data_education = $value['data_education'];
                    $admission_file->data_programme = $value['data_programme'];
                    $admission_file->data_tel = $value['data_tel'];
                    $admission_file->data_email = $value['data_email'];
                    $admission_file->data_gpax = $value['data_gpax'];
                    $admission_file->status = 1;
                    $admission_file->admission_id = $data['admission_id'];
                    $admission_file->save();
                } else {
                    $info_stu = new InformationStudent;
                    $info_stu->id = $value['data_id'];
                    $info_stu->data_first_name = $value['data_first_name'];
                    $info_stu->data_surname = $value['data_surname'];
                    $info_stu->data_degree = 'ม.6';
                    $info_stu->data_email = $value['data_email'];
                    $info_stu->data_school_name = $value['data_school_name'];
                    $info_stu->data_tel = $value['data_tel'];
                    $info_stu->num_of_activity = 0;
                    $info_stu->num_of_admission = 1;
                    $info_stu->save();

                    $admission_file = new AdmissionFile();
                    $admission_file->admission_categories = $value['admission_categories'];
                    $admission_file->admission_name = $value['admission_name'];
                    $admission_file->data_year = $value['data_year'];
                    $admission_file->admission_major = $value['admission_major'];
                    $admission_file->data_id = $value['data_id'];
                    $admission_file->data_first_name = $value['data_first_name'];
                    $admission_file->data_surname = $value['data_surname'];
                    $admission_file->data_gender = $value['data_gender'];
                    $admission_file->data_school_name = $value['data_school_name'];
                    $admission_file->data_province = $value['data_province'];
                    $admission_file->data_education = $value['data_education'];
                    $admission_file->data_programme = $value['data_programme'];
                    $admission_file->data_tel = $value['data_tel'];
                    $admission_file->data_email = $value['data_email'];
                    $admission_file->data_gpax = $value['data_gpax'];
                    $admission_file->status = 1;
                    $admission_file->admission_id = $data['admission_id'];
                    $admission_file->save();
                }
            }
            $information_student_all = InformationStudent::all();
            foreach ($information_student_all as $value) {
                $num_of_admission = count(AdmissionFile::where('data_id', $value['id'])->get());
                InformationStudent::where('id', $value['id'])->update([
                    'num_of_admission' => $num_of_admission
                ]);
                $infomation_stu = InformationStudent::where('id', $value['id'])->first();
                $check_activity = $infomation_stu->num_of_activity;
                $check_admission = $infomation_stu->num_of_admission;
                if ($check_activity == 0 && $check_admission == 0) {
                    InformationStudent::where('id', $value['id'])->delete();
                }
            }
        } else {
            //
            Admission::where('admission_id', $data['admission_id'])->update([
                'admission.admission_major' => $data['admission_major'],
                'admission.admission_year' => $data['admission_year'],
                'admission.entrance_id' => $data['entrance_id'],
                'admission.round_id' => $data['round_id'],
                'admission.program_id' => $data['program_id'],
            ]);
        }

        $college_student = CollegeStudent::all();
        foreach ($college_student as $value) {
            $check_college = CollegeStudentFile::where('college_student_id', $value['college_student_id'])->first();
            if (!$check_college) {
                CollegeStudent::where('college_student_id', $value['college_student_id'])->delete();
            }
        }
    }

    public function deleteAdmission($data)
    {
        foreach ($data['admission_id'] as $value) {
            Admission::where('admission_id', $value)->delete();
            AdmissionFile::where('admission_id', $value)->delete();

            $information_student_all = InformationStudent::all();
            foreach ($information_student_all as $value) {
                $num_of_admission = count(AdmissionFile::where('data_id', $value['id'])->get());
                InformationStudent::where('id', $value['id'])->update([
                    'num_of_admission' => $num_of_admission
                ]);
                $infomation_stu = InformationStudent::where('id', $value['id'])->first();
                $check_activity = $infomation_stu->num_of_activity;
                $check_admission = $infomation_stu->num_of_admission;
                if ($check_activity == 0 && $check_admission == 0) {
                    InformationStudent::where('id', $value['id'])->delete();
                }
            }
        }

        $college_student = CollegeStudent::all();
        foreach ($college_student as $value) {
            $check_college = CollegeStudentFile::where('college_student_id', $value['college_student_id'])->first();
            if (!$check_college) {
                CollegeStudent::where('college_student_id', $value['college_student_id'])->delete();
            }
        }
    }

    public function getAllFileAdmission($admission_id)
    {
        $admission = AdmissionFile::where('admission_id', $admission_id)->get();
        return $admission;
    }

    public function createInformationStudentAdmission($data)
    {
        foreach ($data['admission_file'] as $value) {
            $check = InformationStudent::where('id', $value['data_id'])->first();
            if ($check) {
                $num_of_admission = $check->num_of_admission;
                $new_num_of_admission = $num_of_admission + 1;
                InformationStudent::where('id', $value['data_id'])
                    ->update([
                        'num_of_admission' => $new_num_of_admission,
                        'data_first_name' => $value['data_first_name'],
                        'data_surname' => $value['data_surname'],
                        'data_degree' => 'ม.6',
                        'data_email' => $value['data_email'],
                        'data_school_name' => $value['data_school_name'],
                        'data_tel' => $value['data_tel']

                    ]);
            } else {
                $info_stu = new InformationStudent;
                $info_stu->id = $value['data_id'];
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

    public function create_entrance($data)
    {
        $entrance = new Entrance;
        $entrance->entrance_name = $data['entrance_name'];
        $entrance->entrance_year = $data['entrance_year'];
        $entrance->save();
        foreach ($data['round'] as $value) {
            $round = new RoundName;
            $round->round_name = $value['round_name'];
            $round->entrance_id = $entrance->id;
            $round->save();
            foreach ($value['program'] as $values) {
                $program = new Program;
                $program->program_name = $values['program_name'];
                $program->round_id = $round->id;
                $program->save();
            }
        }
    }

    public function get_entrance($entrance_year)
    {
        $entrance = Entrance::where('entrance_year', $entrance_year)->get();
        foreach ($entrance as $value) {
            $round = RoundName::select('round_id', 'round_name')->where('entrance_id', $value['entrance_id'])->get();
            $value['round'] = $round;
            foreach ($round as $values) {
                $program = Program::select('program_id', 'program_name')->where('round_id', $values['round_id'])->get();
                $values['program'] = $program;
            }
        }
        return $entrance;
    }

    public function updateStatusAdmission($data)
    {
        //1 สมัครเข้าศึกษา
        //2 มีสิทธิ์สอบสัมภาษณ์
        //3 มีสิทธิ์เข้าศึกษา
        //4 ยืนเข้าศึกษา
        //5 ชำระเงิน
        //6 เป็นนักศึกษา

        foreach ($data['data_id'] as $value) {
            AdmissionFile::where('admission_id', $data['admission_id'])->where('data_id', $value)
                ->update(['status' => $data['status']]);
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
