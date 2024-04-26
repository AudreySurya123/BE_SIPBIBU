<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilKuisionerModel_2 extends Model
{
    protected $table = 'hasil_kuis_2'; // Ubah sesuai dengan nama tabel yang digunakanzs
    protected $allowedFields = ['total_skor_internal', 'total_skor_eksternal','hasil_kesimpulan'];
}
