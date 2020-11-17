<?php

namespace App\Repositories;

use App\Model\ActivityStudent;
use App\Model\Activity;
use App\Model\ActivityStudentFile;
use Illuminate\Notifications\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataActivityStudentImport;
use App\Model\AdmissionFile;
use App\Model\DataActivityStudent;

class MatchingRepository implements MatchingRepositoryInterface
{
    public function getAllmatchingActivityAndAdimssion($firstname, $surname)
    {
        $activity = ActivityStudentFile::where('data_first_name', $firstname, 'data_surname', $surname)->get();
        $admission = AdmissionFile::where('data_first_name', $firstname, 'data_surname', $surname)->get();

        $data = array(
            "first_name"

        );
        return $data;
    }
}
