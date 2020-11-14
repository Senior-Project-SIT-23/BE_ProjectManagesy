<?php

namespace App\Repositories;

interface AnalyzeRepositoryInterface
{
    public function numOfActivityAndAdmission($year);
    public function getStudent();
}