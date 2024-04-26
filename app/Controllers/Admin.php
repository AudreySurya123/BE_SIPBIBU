<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\DataAdminModel;

class Admin extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $data_admin = new DataAdminModel;
        return $this->respond(['data_admin' => $data_admin->findAll()], 200);
    }
}
