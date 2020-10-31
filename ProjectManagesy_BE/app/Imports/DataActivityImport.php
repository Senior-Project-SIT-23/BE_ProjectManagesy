<?php

namespace App\Imports;


use App\Model\DataActivity;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataActivityImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DataActivity([
            "data_first_name" => $row["data_first_name"],
            "data_surname" => $row["data_surname"],
            "data_degree" => $row["data_degree"],
            "data_school_name" => $row["data_school_name"],
            "data_from_activity_name" => $row["data_from_activity_name"],
            "data_email" => $row["data_email"],
            "data_tel" => $row["data_tel"],

            //ชื่อcolumในexcelต้องเป็นแบบนี้
        ]);
    }
}