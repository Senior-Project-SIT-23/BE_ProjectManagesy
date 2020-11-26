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
            ->where("admission_year", $year)
            ->groupBy('admission_file.data_school_name')
            ->groupBy('admission_major')->get();

        $temp_activity = ActivityStudent::join('activity_student_file', 'activity_student_file.activity_student_id', '=', 'activity_student.activity_student_id')
            ->selectRaw('activity_student_file.data_school_name, COUNT(*) as num_of_student, activity_student.activity_student_name')
            ->where('activity_student.activity_student_year', $year)
            ->groupBy('activity_student_file.data_school_name')
            ->groupBY('activity_student_name')
            ->get();

        //school admission
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

        //school activity
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

        //

        $data = array(
            "num_of_sit_student" => count($college_student_sit),
            "num_of_it_student" => count($college_student_it),
            "num_of_cs_student" => count($college_student_cs),
            "num_of_dsi_student" => count($college_student_dsi),
            "num_of_admission_student" => count($admission),
            "num_of_activity_student" => count($activity_file),
            "school_admission"  => $data_school_name,
            "school_activity"  => $data_school_activity,

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
