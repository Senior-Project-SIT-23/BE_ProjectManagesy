<?php

namespace App\Repositories;

interface ActivityRepositoryInterface
{
    public function createActivity($data);
    public function getAllActivity();
    public function getActivityById($activity_id);
    public function editActivity($data);
    public function deleteActivity($data);
    public function getAllFileStudentActivity($activity_id);
}