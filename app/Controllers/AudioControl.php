<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class AudioControl extends ResourceController
{
    protected $audioModel = 'App\Models\AudioModel';
    protected $format = 'json';
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */

    // CONTROLLER READ DATA
    public function index()
    {
        $model = new $this->audioModel();
        $data = [
            'message' => 'success',
            'data_audio' => $model->orderby('id', 'DESC')->findAll(), // Menggunakan objek model yang telah dibuat
        ];

        return $this->respond($data, 200);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */

    //CONTROLLER CREATE DATA
    public function create()
    {
        $rules = [
            'judul'          => 'required',
            'deskripsi'      => 'required',
            'sumber'         => 'required',
            'audio_terapis'  => 'uploaded[audio_terapis]|max_size[audio_terapis,100000]|mime_in[audio_terapis,audio/mpeg,audio/wav,audio/ogg,audio/mp3]'
        ];

        if (!$this->validate($rules)) {
            // Jika validasi gagal, kirim respons dengan pesan kesalahan yang jelas
            return $this->failValidationErrors($this->validator->getErrors());
        }

        //PROSES UPLOAD
        $audioFile = $this->request->getFile('audio_terapis');
        if ($audioFile->isValid() && !$audioFile->hasMoved()) {
            //direktori penyimpanan
            $uploadPath = 'uploads'; // Misalnya, simpan di direktori writable/uploads
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Buat direktori dengan izin tertinggi
            }

            $namaAudio = $audioFile->getRandomName();
            $audioFile->move($uploadPath, $namaAudio);

            $model = new $this->audioModel();

            $model->insert([
                'judul'          => esc($this->request->getVar('judul')),
                'deskripsi'      => esc($this->request->getVar('deskripsi')),
                'sumber'         => esc($this->request->getVar('sumber')),
                'audio_terapis'  => $namaAudio
            ]);

            $response = [
                'message' => 'Audio berhasil ditambahkan'
            ];

            return $this->respondCreated($response);
        } else {
            // Jika file gagal diunggah, kirim respons error dengan pesan yang jelas
            return $this->fail('File audio gagal diunggah');
        }
    }


    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */

    //CONTROLLER UPDATE DATA
    public function update($id = null)
    {
        // Validasi input
        $rules = $this->validate([
            'judul'          => 'required',
            'deskripsi'      => 'required',
            'sumber'         => 'required',
            // Hapus validasi audio_terapis
        ]);

        if (!$rules) {
            $response = [
                'message' => $this->validator->getErrors()
            ];
            return $this->failValidationErrors($response);
        }

        // Persiapkan data untuk diupdate
        $data = [
            'judul'      => esc($this->request->getVar('judul')),
            'deskripsi'  => esc($this->request->getVar('deskripsi')),
            'sumber'     => esc($this->request->getVar('sumber')),
        ];

        // Cek apakah ada file audio yang diupload
        if ($audioFile = $this->request->getFile('audio_terapis')) {
            if ($audioFile->isValid() && !$audioFile->hasMoved()) {
                // Generate nama baru untuk audio
                $namaAudio = $audioFile->getRandomName();

                // Pindahkan file audio baru ke direktori uploads
                $audioFile->move('uploads', $namaAudio);

                // Hapus file audio lama jika ada
                $audio = (new $this->audioModel())->find($id);
                if ($audio['audio_terapis'] && file_exists('uploads/' . $audio['audio_terapis'])) {
                    unlink('uploads/' . $audio['audio_terapis']);
                }

                // Tambahkan nama audio baru ke data
                $data['audio_terapis'] = $namaAudio;
            }
        }

        // Update data audio
        $model = new $this->audioModel();
        $model->update($id, $data);

        // Berikan response
        $response = [
            'message' => 'Audio berhasil diubah'
        ];

        return $this->respondCreated($response, 200);
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */

    //CONTROLLER DELETE DATA
    public function delete($id = null)
    {
        $model = new $this->audioModel();

        // Ambil data artikel berdasarkan ID
        $audio = $model->find($id);

        // Cek apakah data artikel ditemukan
        if ($audio) {
            // Hapus gambar cover jika ada
            if (!empty($audio['audio_terapis']) && file_exists('uploads/' . $audio['audio_terapis'])) {
                unlink('uploads/' . $audio['audio_terapis']);
            }

            // Hapus data artikel dari database
            $model->delete($id);

            // Berikan response sukses
            $response = [
                'message' => 'audio berhasil dihapus'
            ];

            return $this->respondDeleted($response);
        } else {
            // Jika data artikel tidak ditemukan, berikan response error
            return $this->failNotFound('Audio tidak ditemukan');
        }
    }
}
