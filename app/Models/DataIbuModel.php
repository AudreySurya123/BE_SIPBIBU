<?php

namespace App\Models;

use CodeIgniter\Model;

class DataIbuModel extends Model
{
    protected $table = 'data_ibu';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'no_telp', 'alamat', 'usia', 'email', 'password'];
}
