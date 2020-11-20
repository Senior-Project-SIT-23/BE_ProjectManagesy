<?php

namespace App\Repositories;

interface ActivityRepositoryInterface
{
    public function createActivity($data);
    public function getAllActivity();
    public function getAllActivityNameList();
    public function editActivity($data);
    public function deleteActivity($data);
    public function createInformationStudentActivity($data);
    public function countActivity($activity_id);
}