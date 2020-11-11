<?php

namespace App\Imports;


use App\Model\DataActivityStudent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataActivityStudentImport implements ToModel, WithHeadingRow
{

    public $activity_student_file_id;

    public function __construct($activity_student_file_id, $file_name, $file_name_random)
    {
        $this->activity_student_file_id = $activity_student_file_id;
        $this->file_name = $file_name;
        $this->file_name_random = $file_name_random;

    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $path = 'activity_student_csv/'. $this->file_name_random;

        return new DataActivityStudent([
            "data_first_name" => $row["data_first_name"],
            "data_surname" => $row["data_surname"],
            "data_degree" => $row["data_degree"],
            "data_school_name" => $row["data_school_name"],
            "data_email" => $row["data_email"],
            "data_tel" => $row["data_tel"],
            "activity_student_id" => $this->activity_student_file_id,
            "data_activity_file_name" => $this->file_name,
            "data_activity_file" => $path,
            "data_keep_file_name" => $this->file_name_random,
            //ชื่อcolumในexcelต้องเป็นแบบนี้
        ]);
    }
}
