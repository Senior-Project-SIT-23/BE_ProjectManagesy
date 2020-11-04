<?php

namespace App\Imports;


use App\Model\DataActivityCollegeStudent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataActivityCollegeStudentImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DataActivityCollegeStudent([
            "data_first_name" => $row["data_first_name"],
            "data_surname" => $row["data_surname"],
            "data_major" => $row["data_major"],
            "data_year" => $row["data_year"],
            "data_high_school_name" => $row["data_high_school_name"],
            "data_form_activity_name" => $row["data_form_activity_name"],
            "data_gpax" => $row["data_gpax"],

            //ชื่อcolumในexcelต้องเป็นแบบนี้
        ]);
    }
}