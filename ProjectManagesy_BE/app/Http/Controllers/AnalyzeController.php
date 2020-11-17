<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AnalyzeRepositoryInterface;
use Illuminate\Support\Facades\Validator;



class AnalyzeController extends Controller
{
    private $analyze;

    public function __construct(AnalyzeRepositoryInterface $analyze)

    {
        $this->analyze = $analyze;
    }

    public function indexNumOfActivityAndAdmission($year)
    {
        $activity = $this->analyze->numOfActivityAndAdmission($year);
        return response()->json($activity, 200);
    }

    public function indexAllStudent()
    {
        $student = $this->analyze->getAllStudent();
        return response()->json($student, 200);
    }

    public function indexStudent($data_first_name,$data_surname)
    {
        $student = $this->analyze->getStudent($data_first_name,$data_surname);
        return response()->json($student, 200);
    }
}
