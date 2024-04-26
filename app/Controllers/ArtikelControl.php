<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ArtikelControl extends ResourceController
{
    protected $artikelModel = 'App\Models\ArtikelModel';
    protected $format = 'json';

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $model = new $this->artikelModel(); // Menggunakan properti artikelModel untuk membuat objek model
        $data = [
        'message' => 'success',
        'data_artikel' =>$model->orderby('id', 'DESC')->findAll(), // Menggunakan objek model yang telah dibuat
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
        $model = new $this->artikelModel(); // Menggunakan properti artikelModel untuk membuat objek model
        $data = [
            'message' => 'success',
            'artikel_byid' =>$model->orderby('id', 'DESC')->find($id), // Menggunakan objek model yang telah dibuat
    ];

    if ($data['artikel_byid'] == null) {
        return $this->failNotFound('Data artikel tidak ditemukan');
    }

    return $this->respond($data, 200);
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $rules = [
            'kategori'      => 'required',
            'judul'         => 'required',
            'isi'           => 'required',
            'cover'         => 'uploaded[cover]|max_size[cover,5120]|is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png]'
        ];
        
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
    
        //PROSES UPLOAD
        $cover = $this->request->getFile('cover');
        if ($cover->isValid() && !$cover->hasMoved()) {
            //direktori penyimpanan
            $uploadPath = 'uploads'; // Misalnya, simpan di direktori writable/uploads
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Buat direktori dengan izin tertinggi
            }
            
            $namaCover = $cover->getRandomName();
            $cover->move($uploadPath, $namaCover);
            
            $model = new $this->artikelModel(); // Membuat objek model
        
            $model->insert([
                'kategori'   => esc($this->request->getVar('kategori')),
                'judul'      => esc($this->request->getVar('judul')),
                'isi'        => esc($this->request->getVar('isi')),
                'cover'      => $namaCover
            ]);
        
            $response = [
                'message' => 'Artikel berhasil ditambahkan'
            ];
        
            return $this->respondCreated($response);
        } else {
            // Jika file gagal diunggah, kirim respons error
            return $this->fail('File gagal diunggah');
        }
    }



    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
{
    // Validasi input
    $rules = $this->validate([
        'kategori'      => 'required',
        'judul'         => 'required',
        'isi'           => 'required',
        // Hapus validasi cover
    ]);

    if (!$rules) {
        $response = [
            'message' => $this->validator->getErrors()
        ];
        return $this->failValidationErrors($response);
    }

    // Persiapkan data untuk diupdate
    $data = [
        'kategori'   => esc($this->request->getVar('kategori')),
        'judul'      => esc($this->request->getVar('judul')),
        'isi'        => esc($this->request->getVar('isi')),
    ];

    // Cek apakah ada file cover yang diupload
    if ($cover = $this->request->getFile('cover')) {
        if ($cover->isValid() && !$cover->hasMoved()) {
            // Generate nama baru untuk cover
            $namaCover = $cover->getRandomName();

            // Pindahkan file cover baru ke direktori uploads
            $cover->move('uploads', $namaCover);

            // Hapus file cover lama jika ada
            $artikel = (new $this->artikelModel())->find($id);
            if ($artikel['cover'] && file_exists('uploads/' . $artikel['cover'])) {
                unlink('uploads/' . $artikel['cover']);
            }

            // Tambahkan nama cover baru ke data
            $data['cover'] = $namaCover;
        }
    }

    // Update data artikel
    $model = new $this->artikelModel();
    $model->update($id, $data);

    // Berikan response
    $response = [
        'message' => 'Artikel berhasil diubah'
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
    public function delete($id = null)
    {
        // Load model artikel
    $model = new $this->artikelModel();

    // Ambil data artikel berdasarkan ID
    $artikel = $model->find($id);

    // Cek apakah data artikel ditemukan
    if ($artikel) {
        // Hapus gambar cover jika ada
        if (!empty($artikel['cover']) && file_exists('uploads/' . $artikel['cover'])) {
            unlink('uploads/' . $artikel['cover']);
        }

        // Hapus data artikel dari database
        $model->delete($id);

        // Berikan response sukses
        $response = [   
            'message' => 'Artikel berhasil dihapus'
        ];

        return $this->respondDeleted($response);
    } else {
        // Jika data artikel tidak ditemukan, berikan response error
        return $this->failNotFound('Artikel tidak ditemukan');
    }
}
    }
