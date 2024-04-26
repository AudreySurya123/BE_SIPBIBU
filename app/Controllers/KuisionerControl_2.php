<?php

namespace App\Controllers;

use App\Models\HasilKuisionerModel_2;
use App\Models\KuisionerModel_2;
use CodeIgniter\RESTful\ResourceController;

class KuisionerControl_2 extends ResourceController
{
    protected $kuisionerModel_2;
    protected $hasilKuisionerModel_2;

    public function __construct()
    {
        $this->kuisionerModel_2 = new KuisionerModel_2(); 
        $this->hasilKuisionerModel_2 = new HasilKuisionerModel_2(); 
    }

    // GET: Retrieve all kuisioner
    public function index()
    {
        return $this->respond($this->getQuestions());
    }

    // Method to get questions from database
    protected function getQuestions()
    {
        $questions = $this->kuisionerModel_2->findAll();
        $data = [];

        foreach ($questions as $question) {
            $data[] = [
                'id' => $question['id'],
                'pernyataan' => $question['pernyataan']
            ];
        }

        return $data;
    }

    // POST: Save kuisioner result
    public function simpanHasil_2()
{
    $json = $this->request->getJSON();
    $jawaban = json_decode(json_encode($json->jawaban), true); // Konversi ke array

    // Pastikan ada jawaban yang dikirim
    if (empty($jawaban)) {
        return $this->fail('Tidak ada jawaban yang dikirim.', 400);
    }

    // Hitung total skor internal (pertanyaan 1-24)
    $totalSkorInternal = array_sum(array_slice($jawaban, 1, 24));

    // Hitung total skor eksternal (pertanyaan 25-32)
    $totalSkorEksternal = array_sum(array_slice($jawaban, 25, 32));

    // Tentukan hasil kesimpulan
    $hasilInternal = ($totalSkorInternal >= 53) ? 'Postpartum blues' : 'Tidak postpartum blues';
    $hasilEksternal = ($totalSkorEksternal >= 19) ? 'Sumber dukungan tidak adekuat' : 'Sumber dukungan adekuat';

    // Simpan hasil kuisioner
    $data = [
        'total_skor_internal' => $totalSkorInternal,
        'total_skor_eksternal' => $totalSkorEksternal,
        'hasil_kesimpulan' => $hasilInternal . ', ' . $hasilEksternal
    ];

    try {
        $this->hasilKuisionerModel_2->insert($data);
        return $this->respondCreated([
            'message' => 'Hasil kuisioner berhasil disimpan.',
            'total_skor_internal' => $totalSkorInternal,
            'total_skor_eksternal' => $totalSkorEksternal,
            'hasil_kesimpulan' => $hasilInternal . ', ' . $hasilEksternal
        ]);
    } catch (\Exception $e) {
        return $this->failServerError('Gagal menyimpan hasil kuisioner: ' . $e->getMessage());
    }
}

public function delete($id = null)
{
    // Cek apakah ada ID yang dikirim
    if ($id === null) {
        return $this->fail('ID tidak boleh kosong');
    }

    // Inisialisasi model
    $hasilKuisionerModel = new HasilKuisionerModel_2();

    // Cek apakah data dengan ID yang diberikan ada
    $data = $hasilKuisionerModel->find($id);

    if (!$data) {
        return $this->fail('Data tidak ditemukan');
    }

    // Hapus data
    try {
        $hasilKuisionerModel->delete($id);
        return $this->respondDeleted([
            'message' => 'Data berhasil dihapus',
            'data' => $data
        ]);
    } catch (\Exception $e) {
        return $this->failServerError('Gagal menghapus data: ' . $e->getMessage());
    }
}

    // GET: Retrieve all hasil kuisioner
    public function read()
    {
        $latestResult = $this->hasilKuisionerModel_2->orderBy('id', 'DESC')->first();

        if ($latestResult === null) {
            return $this->failNotFound('Data hasil kuisioner tidak ditemukan.');
        }

        return $this->respond($latestResult);
    }

    public function readAll()
{
    $hasilKuisionerModel = new HasilKuisionerModel_2();
    $results = $hasilKuisionerModel->findAll();

    if (empty($results)) {
        return $this->failNotFound('Data hasil kuisioner tidak ditemukan.');
    }

    return $this->respond($results);
}
}
