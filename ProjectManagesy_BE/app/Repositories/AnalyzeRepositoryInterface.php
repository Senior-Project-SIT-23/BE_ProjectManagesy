<?php

namespace App\Repositories;

interface AnalyzeRepositoryInterface
{
    public function getAnalyzeByYear($year);
    public function getAllStudent();
    public function getAnalyzeSchoolByYear($year);
}