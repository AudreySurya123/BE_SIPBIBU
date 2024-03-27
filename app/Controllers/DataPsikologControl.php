<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class DataPsikologControl extends ResourceController
{
    protected $DataPsikologModel = 'App\Models\DataPsikologModel';
    protected $format = 'json';


            //IKI CONTROLLER READ DATA!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
{
    $model = new $this->DataPsikologModel(); // Menggunakan properti DataPsikologModel untuk membuat objek model
    $data = [
        'message' => 'success',
        'data_psikolog' =>$model->orderby('id', 'DESC')->findAll(), // Menggunakan objek model yang telah dibuat
    ];

    return $this->respond($data, 200);
}


        //IKI CONTROLLER SHOW DATA GAWE MENCARI PSIKOLOG BERDASARKAN ID !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!



    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        $model = new $this->DataPsikologModel(); // Menggunakan properti DataPsikologModel untuk membuat objek model
        $data = [
            'message' => 'success',
            'psikolog_byid' =>$model->orderby('id', 'DESC')->find($id), // Menggunakan objek model yang telah dibuat
    ];

    if ($data['psikolog_byid'] == null) {
        return $this->failNotFound('Data psikolog tidak ditemukan');
    }

    return $this->respond($data, 200);
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */

        //IKI CONTROLLER CREATE DATA !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!



    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {   
        $rules = [
            'nama'          => 'required',
            'email'         => 'required',
            'password'      => 'required',
            'no_telepon'    => 'required',
            'alamat'        => 'required',
            'kelamin'       => 'required',
            'sertifikat'    => 'uploaded[sertifikat]|max_size[sertifikat,5120]|is_image[sertifikat]|mime_in[sertifikat,image/jpg,image/jpeg,image/png]'
        ];
        
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
    
        //PROSES UPLOAD
        $sertifikat = $this->request->getFile('sertifikat');
        if ($sertifikat->isValid() && !$sertifikat->hasMoved()) {
            //direktori penyimpanan
            $uploadPath = 'uploads'; // Misalnya, simpan di direktori writable/uploads
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Buat direktori dengan izin tertinggi
            }
            
            $namaSertifikat = $sertifikat->getRandomName();
            $sertifikat->move($uploadPath, $namaSertifikat);
            
            $model = new $this->DataPsikologModel(); // Membuat objek model
        
            $model->insert([
                'nama'       => esc($this->request->getVar('nama')),
                'email'      => esc($this->request->getVar('email')),
                'password'   => esc($this->request->getVar('password')),
                'no_telepon' => esc($this->request->getVar('no_telepon')),
                'alamat'     => esc($this->request->getVar('alamat')),
                'kelamin'    => esc($this->request->getVar('kelamin')),
                'sertifikat' => $namaSertifikat
            ]);
        
            $response = [
                'message' => 'Psikolog berhasil ditambahkan'
            ];
        
            return $this->respondCreated($response);
        } else {
            // Jika file gagal diunggah, kirim respons error
            return $this->fail('File gagal diunggah');
        }
    }



    //IKI CONTROLLER UPDATE DATA!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


    public function update($id = null)
{
    // Validasi input
    $rules = $this->validate([
        'nama'          => 'required',
        'email'         => 'required',
        'password'      => 'required',
        'no_telepon'    => 'required',
        'alamat'        => 'required',
        'kelamin'       => 'required',
        'sertifikat'    => 'uploaded[sertifikat]|max_size[sertifikat,3048]|is_image[sertifikat]|mime_in[sertifikat,image/jpg,image/jpeg,image/png]'
    ]);

    if (!$rules) {
        $response = [
            'message' => $this->validator->getErrors()
        ];
        return $this->failValidationErrors($response);
    }

    // Ambil file sertifikat
    $sertifikat = $this->request->getFile('sertifikat');

    // Cek apakah ada file sertifikat yang diupload
    if ($sertifikat->isValid() && !$sertifikat->hasMoved()) {
        // Generate nama baru untuk sertifikat
        $namaSertifikat = $sertifikat->getRandomName();

        // Pindahkan file sertifikat baru ke direktori uploads
        $sertifikat->move('uploads', $namaSertifikat);

        // Persiapkan data untuk diupdate, termasuk nama sertifikat baru
        $data = [
            'nama'       => esc($this->request->getVar('nama')),
            'email'      => esc($this->request->getVar('email')),
            'password'   => esc($this->request->getVar('password')),
            'no_telepon' => esc($this->request->getVar('no_telepon')),
            'alamat'     => esc($this->request->getVar('alamat')),
            'kelamin'    => esc($this->request->getVar('kelamin')),
            'sertifikat' => $namaSertifikat
        ];

        // Ambil data psikolog yang akan diupdate
        $model = new $this->DataPsikologModel();
        $psikolog = $model->find($id);

        // Hapus file sertifikat lama jika ada dan update data psikolog
        if ($psikolog['sertifikat'] && file_exists('uploads/' . $psikolog['sertifikat'])) {
            unlink('uploads/' . $psikolog['sertifikat']);
        }

        $model->update($id, $data);

        // Berikan response
        $response = [
            'message' => 'Psikolog berhasil diubah'
        ];

        return $this->respondCreated($response, 200);
    } else {
        // Gunakan nama sertifikat lama jika tidak ada file yang diupload
        $namaSertifikat = $this->request->getPost('sertifikatLama');

        // Persiapkan data untuk diupdate, menggunakan nama sertifikat lama
        $data = [
            'nama'       => esc($this->request->getVar('nama')),
            'email'      => esc($this->request->getVar('email')),
            'password'   => esc($this->request->getVar('password')),
            'no_telepon' => esc($this->request->getVar('no_telepon')),
            'alamat'     => esc($this->request->getVar('alamat')),
            'kelamin'    => esc($this->request->getVar('kelamin')),
            'sertifikat' => $namaSertifikat
        ];

        // Update data psikolog tanpa mengubah sertifikat
        $model = new $this->DataPsikologModel();
        $model->update($id, $data);

        // Berikan response
        $response = [
            'message' => 'Psikolog berhasil diubah'
        ];

        return $this->respondCreated($response, 200);
    }
}


        // IKI CONTROLLER DELETE DATA!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
{
    // Load model psikolog
    $model = new $this->DataPsikologModel();

    // Ambil data psikolog berdasarkan ID
    $psikolog = $model->find($id);

    // Cek apakah data psikolog ditemukan
    if ($psikolog) {
        // Hapus gambar sertifikat jika ada
        if (!empty($psikolog['sertifikat']) && file_exists('uploads/' . $psikolog['sertifikat'])) {
            unlink('uploads/' . $psikolog['sertifikat']);
        }

        // Hapus data psikolog dari database
        $model->delete($id);

        // Berikan response sukses
        $response = [
            'message' => 'Psikolog berhasil dihapus'
        ];

        return $this->respondDeleted($response);
    } else {
        // Jika data psikolog tidak ditemukan, berikan response error
        return $this->failNotFound('Psikolog tidak ditemukan');
    }
}
}
