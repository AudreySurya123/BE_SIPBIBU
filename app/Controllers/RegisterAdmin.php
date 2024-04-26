<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\DataAdminModel;

class RegisterAdmin extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $rules = [
            'nama' => 'required|min_length[4]|max_length[255]',
            'email' => 'required|min_length[4]|max_length[255]|valid_email|is_unique[data_admin.email]',
            'password' => 'required|'
        ];

        if ($this->validate($rules)) {
            $model = new DataAdminModel();
            $data = [
                'nama'     => $this->request->getVar('nama'),
                'email'    => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];
            $model->save($data);

            return $this->respond(['message' => 'Registered Successfully'], 200);
        } else {
            $response = [
                'errors' => $this->validator->getErrors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->fail($response, 409);
        }
    }
}