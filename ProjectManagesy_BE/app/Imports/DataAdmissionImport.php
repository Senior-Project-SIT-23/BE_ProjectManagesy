<?php

namespace App\Imports;


use App\Model\DataAdmission;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataAdmissionImport implements ToModel, WithHeadingRow
{
    public $admission_file_id;

    public function __construct($admission_file_id, $file_name, $file_name_random)
    {
        $this->admission_file_id = $admission_file_id;
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
        $path = 'admission_csv/' . $this->file_name_random;
        
        return new DataAdmission([
            "data_first_name" => $row["data_first_name"],
            "data_surname" => $row["data_surname"],
            "data_school_name" => $row["data_school_name"],
            "data_year" => $row["data_year"],
            "data_major" => $row["data_major"],
            "data_gpax" => $row["data_gpax"],
            "admission_id" => $this->admission_file_id,
            "data_admission_file_name" => $this->file_name,
            "data_admission_file" => $path,
            "data_keep_file_name" => $this->file_name_random

            //ชื่อcolumในexcelต้องเป็นแบบนี้
        ]);
    }
}
