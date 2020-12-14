<?php

namespace App\Repositories;

use App\Model\ActivityStudent;
use App\Model\ActivityStudentFile;
use App\Model\Admission;
use App\Model\AdmissionFile;
use App\Model\CollegeStudent;
use App\Model\InformationStudent;
use App\Model\CollegeStudentFile;
use App\Model\Entrance;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Model\Program;

class AnalyzeRepository implements AnalyzeRepositoryInterface
{
    public function getAnalyzeByYear($year)
    {
        $college_student_sit = CollegeStudentFile::where('data_entrance_year', $year)->get();

        $college_student_it = CollegeStudentFile::where('data_entrance_year', $year)
            ->where('data_major', 'IT')->get();
        $college_student_cs = CollegeStudentFile::where('data_entrance_year', $year)
            ->where('data_major', 'CS')->get();
        $college_student_dsi = CollegeStudentFile::where('data_entrance_year', $year)
            ->where('data_major', 'DSI')->get();
        $admission = Admission::where('admission_year', $year)
            ->join('admission_file', 'admission_file.admission_id', '=', 'admission.admission_id')->get();

        $activity_file = ActivityStudentFile::select('data_id')->distinct()->get();

        $gender_it_male = count(CollegeStudentFile::where('data_entrance_year', $year)
            ->where('data_major', 'IT')->where('data_gender', 'ชาย')->get());
        $gender_it_female = count(CollegeStudentFile::where('data_entrance_year', $year)->where('data_gender', 'หญิง')
            ->where('data_major', 'IT')->get());
        $total_gender_it = count(CollegeStudentFile::where('data_entrance_year', $year)
            ->where('data_major', 'IT')->get());
        $gender_cs_male = count(CollegeStudentFile::where('data_entrance_year', $year)
            ->where('data_major', 'CS')->where('data_gender', 'ชาย')->get());
        $gender_cs_female = count(CollegeStudentFile::where('data_entrance_year', $year)->where('data_gender', 'หญิง')
            ->where('data_major', 'CS')->get());
        $total_gender_cs = count(CollegeStudentFile::where('data_entrance_year', $year)
            ->where('data_major', 'CS')->get());
        $gender_dsi_male = count(CollegeStudentFile::where('data_entrance_year', $year)
            ->where('data_major', 'DSI')->where('data_gender', 'ชาย')->get());
        $gender_dsi_female = count(CollegeStudentFile::where('data_entrance_year', $year)->where('data_gender', 'หญิง')
            ->where('data_major', 'DSI')->get());
        $total_gender_dsi = count(CollegeStudentFile::where('data_entrance_year', $year)
            ->where('data_major', 'DSI')->get());
        $all_gender_male = count(CollegeStudentFile::where('data_entrance_year', $year)
            ->where('data_gender', 'ชาย')->get());
        $all_gender_female = count(CollegeStudentFile::where('data_entrance_year', $year)
            ->where('data_gender', 'หญิง')->get());
        $all_gender = count(CollegeStudentFile::where('data_entrance_year', $year)->get());

        $gender = (array)array(
            [
                'Major' => 'IT',
                'Male' => "$gender_it_male",
                'Female' => "$gender_it_female",
                'Total' => "$total_gender_it"
            ],
            [
                'Major' => 'CS',
                'Male' => "$gender_cs_male",
                'Female' => "$gender_cs_female",
                'Total' => "$total_gender_cs"
            ],
            [
                'Major' => 'DSI',
                'Male' => "$gender_dsi_male",
                'Female' => "$gender_dsi_female",
                'Total' => "$total_gender_dsi"
            ],
            [
                'Major' => 'All',
                'Male' => "$all_gender_male",
                'Female' => "$all_gender_female",
                'Total' => "$all_gender"
            ],
        );

        //Most Province
        $college_province = CollegeStudentFile::selectRaw('data_province, count(data_province) as num_of_province')
            ->where('data_entrance_year', $year)
            ->groupBy('data_province')->get();

        //compare_activity
        $student = CollegeStudentFile::where('data_entrance_year', $year)->get();
        $used_to_activity = 0;
        $non_activity = 0;
        foreach ($student as $value) {
            $activity_student = ActivityStudentFile::where('data_id', $value['data_id'])->first();
            if ($activity_student) {
                $used_to_activity++;
            } else {
                $non_activity++;
            }
        }
        $activity = (array)array(
            [
                'activity' => "$used_to_activity",
                'non_activity' => "$non_activity"
            ]
        );

        //5 sequence most activity
        $keep_activity = [];
        foreach ($student as $value) {
            $activity_student_file = ActivityStudentFile::where('data_id', $value['data_id'])->first();
            if ($activity_student_file) {
                $activity_student = ActivityStudent::where('activity_student_id', $activity_student_file->activity_student_id)->first();
                $activity_student_name = $activity_student->activity_student_name;
                if (Arr::has($keep_activity, $activity_student_name)) {
                    $keep_activity["$activity_student_name"]['Total']++;
                } else {
                    $keep_activity["$activity_student_name"]['activity_name'] = $activity_student_name;
                    $keep_activity["$activity_student_name"]['Total'] = 1;
                }
            }
        }
        $data_most_activity = [];
        foreach ($keep_activity as  $item) {
            array_push($data_most_activity, $item);
        }

        $temp_admission = Admission::join('admission_file', 'admission_file.admission_id', '=', 'admission.admission_id')
            ->selectRaw('count(admission.admission_major) as num_of_student, admission_file.data_school_name, admission.admission_major')
            ->where("admission_year", $year)
            ->groupBy('admission_file.data_school_name')
            ->groupBy('admission_major')
            ->get();


        $temp_activity = ActivityStudent::join('activity_student_file', 'activity_student_file.activity_student_id', '=', 'activity_student.activity_student_id')
            ->selectRaw('activity_student_file.data_school_name, COUNT(*) as num_of_student, activity_student.activity_student_name')
            ->groupBy('activity_student_file.data_school_name')
            ->groupBY('activity_student_name')
            ->get();

        $temp_admission_name = CollegeStudentFile::where('data_entrance_year', $year)
            ->selectRaw('data_admission, data_major, COUNT(data_admission) as num_of_admission_name')
            ->groupBy('data_admission')
            ->groupBy('data_major')
            ->get();

        //เพิ่ม เรียกheader
        $temp_header = ActivityStudent::selectRaw('activity_student.activity_student_name')
            ->groupBy('activity_student_name')
            ->groupBy('activity_student_major')
            ->get();

        $temp_college_student = CollegeStudentFile::where('data_entrance_year', $year)
            ->selectRaw('data_school_name, data_major, count(data_major) as num_of_student ')
            ->groupBy('data_school_name')
            ->groupBy('data_major')
            ->get();

        // //school admission
        $temp = [];
        foreach ($temp_admission as  $item) {
            if (Arr::has($temp, "$item[data_school_name]")) {
                if ($item["admission_major"] == 'IT') {
                    $temp["$item[data_school_name]"]['IT'] += $item['num_of_student'];
                } else if ($item["admission_major"] == 'CS') {
                    $temp["$item[data_school_name]"]['CS'] += $item['num_of_student'];
                } else {
                    $temp["$item[data_school_name]"]['DSI'] += $item['num_of_student'];
                }
                $temp["$item[data_school_name]"]['SUM']  += $item['num_of_student'];
            } else {
                $temp["$item[data_school_name]"] = array();
                $temp["$item[data_school_name]"]['IT'] = 0;
                $temp["$item[data_school_name]"]['CS'] = 0;
                $temp["$item[data_school_name]"]['DSI'] = 0;
                $temp["$item[data_school_name]"]['SUM'] = 0;
                $temp["$item[data_school_name]"]['data_school_name'] = $item["data_school_name"];
                if ($item["admission_major"] == 'IT') {
                    $temp["$item[data_school_name]"]['IT'] += $item['num_of_student'];
                } else if ($item["admission_major"] == 'CS') {
                    $temp["$item[data_school_name]"]['CS'] += $item['num_of_student'];
                } else {
                    $temp["$item[data_school_name]"]['DSI'] += $item['num_of_student'];
                }
                $temp["$item[data_school_name]"]['SUM']  += $item['num_of_student'];
            }
        }

        $data_school_name = [];
        foreach ($temp as  $item) {
            array_push($data_school_name, $item);
        }
        //

        // //SELECT activity_student_name FROM `activity_student` WHERE activity_student_year = 2563 คือ header เพื่อดูว่าในปีนี้มี activity อะไรบ้าง แล้วส่ง header นี้กลับไปด้วย
        $school = [];
        foreach ($temp_activity as $value) {
            if (Arr::has($school, "$value[data_school_name]")) {
                $school["$value[data_school_name]"]["$value[activity_student_name]"] = $value['num_of_student'];
                $school["$value[data_school_name]"]['SUM'] += $value['num_of_student'];
            } else {
                $school["$value[data_school_name]"] = array();
                $school["$value[data_school_name]"]['data_school_name'] = $value["data_school_name"];
                $school["$value[data_school_name]"]["$value[activity_student_name]"] = $value['num_of_student'];
                $school["$value[data_school_name]"]['SUM'] = $value['num_of_student'];
            }
        }

        $data_school_activity = [];
        foreach ($school as $value) {
            array_push($data_school_activity, $value);
        }


        // school_admission_name
        $admission_name = [];
        foreach ($temp_admission_name as $value) {
            if (Arr::has($admission_name, "$value[data_admission]")) {
                if ($value["data_major"] == 'IT') {
                    $admission_name["$value[data_admission]"]['IT'] += $value['num_of_admission_name'];
                } else if ($value["data_major"] == 'CS') {
                    $admission_name["$value[data_admission]"]['CS'] += $value['num_of_admission_name'];
                } else {
                    $admission_name["$value[data_admission]"]['DSI'] += $value['num_of_admission_name'];
                }
                $admission_name["$value[data_admission]"]['SUM']  += $value['num_of_admission_name'];
            } else {
                $admission_name["$value[data_admission]"] = array();
                $admission_name["$value[data_admission]"]['IT'] = 0;
                $admission_name["$value[data_admission]"]['CS'] = 0;
                $admission_name["$value[data_admission]"]['DSI'] = 0;
                $admission_name["$value[data_admission]"]['SUM'] = 0;
                $admission_name["$value[data_admission]"]['admission_name'] = $value["data_admission"];
                if ($value["data_major"] == 'IT') {
                    $admission_name["$value[data_admission]"]['IT'] += $value['num_of_admission_name'];
                } else if ($value["data_major"] == 'CS') {
                    $admission_name["$value[data_admission]"]['CS'] += $value['num_of_admission_name'];
                } else {
                    $admission_name["$value[data_admission]"]['DSI'] += $value['num_of_admission_name'];
                }
                $admission_name["$value[data_admission]"]['SUM']  += $value['num_of_admission_name'];
            }
        }

        $data_school_admission_name = [];
        foreach ($admission_name as  $item) {
            array_push($data_school_admission_name, $item);
        }
        //

        //college student
        $college = [];
        foreach ($temp_college_student as $value) {
            if (Arr::has($college, "$value[data_school_name]")) {
                if ($value["data_major"] == 'IT') {
                    $college["$value[data_school_name]"]['IT'] += $value['num_of_student'];
                } else if ($value["data_major"] == 'CS') {
                    $college["$value[data_school_name]"]['CS'] += $value['num_of_student'];
                } else {
                    $college["$value[data_school_name]"]['DSI'] += $value['num_of_student'];
                }
                $college["$value[data_school_name]"]['SUM']  += $value['num_of_student'];
            } else {
                $college["$value[data_school_name]"] = array();
                $college["$value[data_school_name]"]['IT'] = 0;
                $college["$value[data_school_name]"]['CS'] = 0;
                $college["$value[data_school_name]"]['DSI'] = 0;
                $college["$value[data_school_name]"]['SUM'] = 0;
                $college["$value[data_school_name]"]['data_school_name'] = $value["data_school_name"];
                if ($value["data_major"] == 'IT') {
                    $college["$value[data_school_name]"]['IT'] += $value['num_of_student'];
                } else if ($value["data_major"] == 'CS') {
                    $college["$value[data_school_name]"]['CS'] += $value['num_of_student'];
                } else {
                    $college["$value[data_school_name]"]['DSI'] += $value['num_of_student'];
                }
                $college["$value[data_school_name]"]['SUM']  += $value['num_of_student'];
            }
        }

        $data_college_student = [];
        foreach ($college as  $item) {
            array_push($data_college_student, $item);
        }

        //

        $data = array(
            "num_of_sit_student" => count($college_student_sit),
            "num_of_it_student" => count($college_student_it),
            "num_of_cs_student" => count($college_student_cs),
            "num_of_dsi_student" => count($college_student_dsi),
            "num_of_admission_student" => count($admission),
            "num_of_activity_student" => count($activity_file),
            "gender" => $gender,
            "most_of_province" => $college_province,
            "compare_activity" => $activity,
            "most_of_activity" => $data_most_activity,
            "school_admission"  => $data_school_name,
            "school_activity"  => $data_school_activity,
            "school_admission_name"  => $data_school_admission_name,
            "Header" => $temp_header, //เรียกheader
            "college_student" => $data_college_student
        );

        return $data;
    }
    public function getAnalyzeSchoolByYear($year)
    {
    }

    public function getAllStudent()
    {
        $student = InformationStudent::all();
        foreach ($student as $value) {

            $activity_file = ActivityStudentFile::where('data_id', $value['id'])
                ->join('activity_student', 'activity_student.activity_student_id', '=', 'activity_student_file.activity_student_id')->get();
            $admission_file = AdmissionFile::where('data_id', $value['id'])
                ->join('admission', 'admission.admission_id', '=', 'admission_file.admission_id')->get();

            $value['activity'] = $activity_file;
            $value['admission'] = $admission_file;
        }

        return $student;
    }

    public function getIndex($temp, $name)
    {
        for ($i = 0; $i < count($temp); $i++) {
            if ($temp[$i]['data_school_name'] == $name) {
                return $i;
            } else {
                return -1;
            }
        }
    }
}
