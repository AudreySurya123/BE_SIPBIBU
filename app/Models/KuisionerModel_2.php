<?php

namespace App\Models;

use CodeIgniter\Model;

class KuisionerModel_2 extends Model
{
    protected $table = 'kuis2'; // Ubah ke 'kuis' sesuai dengan nama tabel yang digunakan
    protected $allowedFields = ['pernyataan'];

    public function simpanHasil_2($data)
    {
        return $this->insert($data);
    }
}

