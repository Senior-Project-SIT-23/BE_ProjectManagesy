<?php

namespace App\Imports;


use App\Model\DataAdmission;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataAdmissionImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DataAdmission([
            "data_first_name" => $row["data_first_name"],
            "data_surname" => $row["data_surname"],
            "data_school_name" => $row["data_school_name"],
            "data_year" => $row["data_year"],
            "data_major" => $row["data_major"],
            "data_gpax" => $row["data_gpax"],

            //ชื่อcolumในexcelต้องเป็นแบบนี้
        ]);
    }
}