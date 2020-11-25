<?php

namespace App\Repositories;

use App\Model\ActivityStudent;
use App\Model\ActivityStudentFile;
use App\Model\Admission;
use App\Model\AdmissionFile;
use App\Model\InformationStudent;
use App\Model\CollegeStudentFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use stdClass;

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
        $activity_file = ActivityStudentFile::where('data_year', $year)->select('data_id')->distinct()->get();

        $temp_admission = Admission::join('admission_file', 'admission_file.admission_id', '=', 'admission.admission_id')
            ->selectRaw('count(admission.admission_major) as num_of_student, admission_file.data_school_name, admission.admission_major')
            ->groupBy('admission_file.data_school_name')
            ->groupBy('admission_major')->get();



        // for ($i = 0; $i < count($temp_admission); $i++) {
        //     if ($i == 0) {
        //         if ($temp_admission[$i]['admission_major'] == 'IT') {
        //             $data = array(
        //                 'data_school_name' => $temp_admission[$i]['data_school_name'],
        //                 'IT' => 1,
        //                 'CS' => 0,
        //                 'DSI' => 0
        //             );
        //             array_push($temp, $data);
        //         } else if ($temp_admission[$i]['admission_major'] == 'CS') {
        //             $data = array(
        //                 'data_school_name' => $temp_admission[$i]['data_school_name'],
        //                 'IT' => 0,
        //                 'CS' => 1,
        //                 'DSI' => 0
        //             );
        //             array_push($temp, $data);
        //         } else {
        //             $data = array(
        //                 'data_school_name' => $temp_admission[$i]['data_school_name'],
        //                 'IT' => 0,
        //                 'CS' => 0,
        //                 'DSI' => 1
        //             );
        //             array_push($temp, $data);
        //         }
        //     } else {
        //         // $index = array_filter
        //         // dd($index);
        //         if ($index != -1) {
        //             if ($temp_admission[$i]['admission_major'] == 'IT') {
        //                 $temp[$index]['IT'] = 5;
        //             } else if ($temp_admission[$i]['admission_major'] == 'CS') {
        //                 $temp[$index]['CS'] = 5;
        //             } else {
        //                 $temp[$index]['DSI'] = 5;
        //             }
        //         } else {
        //             if ($temp_admission[$i]['admission_major'] == 'IT') {
        //                 $data = array(
        //                     'data_school_name' => $temp_admission[$i]['data_school_name'],
        //                     'IT' => 1,
        //                     'CS' => 0,
        //                     'DSI' => 0
        //                 );
        //                 array_push($temp, $data);
        //             } else if ($temp_admission[$i]['admission_major'] == 'CS') {
        //                 $data = array(
        //                     'data_school_name' => $temp_admission[$i]['data_school_name'],
        //                     'IT' => 0,
        //                     'CS' => 1,
        //                     'DSI' => 0
        //                 );
        //                 array_push($temp, $data);
        //             } else {
        //                 $data = array(
        //                     'data_school_name' => $temp_admission[$i]['data_school_name'],
        //                     'IT' => 0,
        //                     'CS' => 0,
        //                     'DSI' => 1
        //                 );
        //                 array_push($temp, $data);
        //             }
        //         }
        //     }
        // }
        $temp = [];
        foreach ($temp_admission as  $item) {
            if (Arr::has($temp, "$item[data_school_name]")) {
                if ($temp["$item[data_school_name]"]['IT'] == 'IT') {
                    $temp["$item[data_school_name]"]['IT'] += $item['num_of_student'];
                } else if ($temp["$item[data_school_name]"]['CS'] == 'CS') {
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

                if ($temp["$item[data_school_name]"]['IT'] == 'IT') {
                    $temp["$item[data_school_name]"]['IT'] += $item['num_of_student'];
                } else if ($temp["$item[data_school_name]"]['cs'] == 'CS') {
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



        $data = array(
            "num_of_sit_student" => count($college_student_sit),
            "num_of_it_student" => count($college_student_it),
            "num_of_cs_student" => count($college_student_cs),
            "num_of_dsi_student" => count($college_student_dsi),
            "num_of_admission_student" => count($admission),
            "num_of_activity_student" => count($activity_file),
            "school_admission"  => $data_school_name,
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
