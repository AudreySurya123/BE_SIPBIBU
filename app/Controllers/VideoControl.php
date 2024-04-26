<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class VideoControl extends ResourceController
{
    protected $videoModel = 'App\Models\VideoModel';
    protected $format = 'json';
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */

    // CONTROLLER READ DATA
    public function index()
    {
        $model = new $this->videoModel();
        $data = [
            'message' => 'success',
            'data_video' => $model->orderby('id', 'DESC')->findAll(), // Menggunakan objek model yang telah dibuat
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

    // CONTROLLER CREATE DATA
    public function create()
    {
        $rules = [
            'judul'          => 'required',
            'deskripsi'      => 'required',
            'sumber'         => 'required',
            'video_terapis'  => 'uploaded[video_terapis]|max_size[video_terapis,100000]|mime_in[video_terapis,video/mp4,video/x-matroska,video/webm,video/quicktime]'
        ];

        if (!$this->validate($rules)) {
            // Jika validasi gagal, kirim respons dengan pesan kesalahan yang jelas
            return $this->failValidationErrors($this->validator->getErrors());
        }

        //PROSES UPLOAD
        $videoFile = $this->request->getFile('video_terapis');
        if ($videoFile->isValid() && !$videoFile->hasMoved()) {
            //direktori penyimpanan
            $uploadPath = 'uploads'; // Misalnya, simpan di direktori writable/uploads/videos
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Buat direktori dengan izin tertinggi
            }

            $namaVideo = $videoFile->getRandomName();
            $videoFile->move($uploadPath, $namaVideo);

            $model = new $this->videoModel();

            $model->insert([
                'judul'          => esc($this->request->getVar('judul')),
                'deskripsi'      => esc($this->request->getVar('deskripsi')),
                'sumber'         => esc($this->request->getVar('sumber')),
                'video_terapis'  => $namaVideo
            ]);

            $response = [
                'message' => 'Video berhasil ditambahkan'
            ];

            return $this->respondCreated($response);
        } else {
            // Jika file gagal diunggah, kirim respons error dengan pesan yang jelas
            return $this->fail('File video gagal diunggah');
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

    // CONTROLLER UPDATE DATA
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

        // Cek apakah ada file video yang diupload
        if ($videoFile = $this->request->getFile('video_terapis')) {
            if ($videoFile->isValid() && !$videoFile->hasMoved()) {
                // Generate nama baru untuk video
                $namaVideo = $videoFile->getRandomName();

                // Pindahkan file video baru ke direktori uploads
                $videoFile->move('uploads', $namaVideo);

                // Hapus file video lama jika ada
                $video = (new $this->videoModel())->find($id);
                if ($video['video_terapis'] && file_exists('uploads/' . $video['video_terapis'])) {
                    unlink('uploads/' . $video['video_terapis']);
                }

                // Tambahkan nama video baru ke data
                $data['video_terapis'] = $namaVideo;
            }
        }

        // Update data video
        $model = new $this->videoModel();
        $model->update($id, $data);

        // Berikan response
        $response = [
            'message' => 'Video berhasil diubah'
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

    // CONTROLLER DELETE DATA
    public function delete($id = null)
    {
        $model = new $this->videoModel();

        // Ambil data artikel berdasarkan ID
        $video = $model->find($id);

        // Cek apakah data artikel ditemukan
        if ($video) {
            // Hapus gambar cover jika ada
            if (!empty($video['video_terapis']) && file_exists('uploads/' . $video['video_terapis'])) {
                unlink('uploads/' . $video['video_terapis']);
            }

            // Hapus data artikel dari database
            $model->delete($id);

            // Berikan response sukses
            $response = [
                'message' => 'Video berhasil dihapus'
            ];

            return $this->respondDeleted($response);
        } else {
            // Jika data artikel tidak ditemukan, berikan response error
            return $this->failNotFound('Video tidak ditemukan');
        }
    }
}
