<?php

namespace App\Imports;

use App\Model\Activity;
use Maatwebsite\Excel\Concerns\ToModel;

class ActivityImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Activity([
            "activity_name" => $row[1],
            "activity_major" => $row[1]
        ]);
    }
}
