<?php

namespace App\Models;

use CodeIgniter\Model;

class DataPsikologModel extends Model
{
    protected $table            = 'psikolog';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
    'nama',
    'password',
    'email',
    'no_telepon',
    'alamat',
    'kelamin',
    'sertifikat'
];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

}
