<?php

namespace App\Repositories;

interface AnalyzeRepositoryInterface
{
    public function numOfActivityAndAdmission($year);
    public function getAllStudent();
    public function getStudent($data_first_name,$data_surname);
}