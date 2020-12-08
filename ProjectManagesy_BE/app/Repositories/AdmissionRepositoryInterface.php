<?php

namespace App\Repositories;

interface AdmissionRepositoryInterface
{
    public function createAdmission($data);
    public function getAllAdmission();
    public function getAdmissionById($admission_id);
    public function editAdmission($data);
    public function deleteAdmission($data);
    public function getAllFileAdmission($admission_id);
    public function createInformationStudentAdmission($data);

    public function create_entrance($data);
    public function get_entrance();
}
