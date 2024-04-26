<?php

namespace App\Models;

use CodeIgniter\Model;

class KuisionerModel extends Model
{
    protected $table = 'kuis'; // Ubah ke 'kuis' sesuai dengan nama tabel yang digunakan
    protected $allowedFields = ['pernyataan'];

    public function simpanHasil($data)
    {
        return $this->insert($data);
    }
}

