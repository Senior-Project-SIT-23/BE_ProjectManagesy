<?php

namespace App\Repositories;

interface AdmissionRepositoryInterface
{
    public function createAdmission($data);  
    public function getAllAdmission();
    public function getAdmissionById($admission_id);
    public function editAdmission($data);

}