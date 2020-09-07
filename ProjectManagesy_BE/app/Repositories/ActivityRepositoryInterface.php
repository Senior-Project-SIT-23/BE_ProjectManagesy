<?php

namespace App\Repositories;

interface ActivityRepositoryInterface
{
    public function createActivity($data);
    public function getAllActivity();
}