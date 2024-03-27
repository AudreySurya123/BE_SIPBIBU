<?php

namespace App\Controllers;

use App\Models\DataAdminModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class DataAdminControl extends ResourceController
{
    use ResponseTrait;
    protected $format = 'json';

    public function index()
    {
        $model = new DataAdminModel();
        $data = $model->findAll();

        if (!empty($data)) {
            $response = [
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'data' => $data
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No data found',
                'data' => []
            ];
        }

        return $this->respond($response);
    }

    public function create()
    {
        $data = [
            'nama' => $this->request->getVar('nama'),
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
        ];

        $model = new DataAdminModel();
        $model->save($data);

        $response = [
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan',
            'data' => $data,
        ];

        return $this->respond($response);
    }

    public function update($id = null)
    {
        $model = new \App\Models\DataAdminModel();
        $data_admin = $model->find($id);
        if ($data_admin) {
            $data = [
                'nama' => $this->request->getVar('nama'),
                'email' => $this->request->getVar('email'),
                'password' => $this->request->getVar('password'),
            ];
            $proses = $model->update($id, $data); // Perubahan pada baris ini
            if ($proses) {
                $response = [
                    'status' => 200,
                    'messages' => 'Data berhasil diubah',
                    'data' => $data
                ];
            } else {
                $response = [
                    'status' => 402,
                    'messages' => 'Gagal diubah',
                ];
            }
            return $this->respond($response);
        }
        return $this->failNotFound('Data tidak ditemukan');
    }

    public function delete($id = null)
    {
        $model = new \App\Models\DataAdminModel();
        $data_admin = $model->find($id);
        if ($data_admin) {
            $proses = $model->delete($id);
            if ($proses) {
                $response = [
                    'status' => 200,
                    'messages' => 'Data berhasil dihapus',
                ];
            } else {
                $response = [
                    'status' => 402,
                    'messages' => 'Gagal menghapus data',
                ];
            }
            return $this->respond($response);
        } else {
            return $this->failNotFound('Data tidak ditemukan');
        }
    }
}

