<?php

namespace App\Repositories;

interface CollegeStudentRepositoryInterface
{
    public function createCollegeStudent($data);
    public function editCollegeStudent($data);
    public function deleteCollegeStudent($data);
    public function getAllCollegeStudent();
}
