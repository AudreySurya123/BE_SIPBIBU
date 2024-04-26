<?php

namespace App\Models;

use CodeIgniter\Model;

class AudioModel extends Model
{
    protected $table            = 'audio';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['judul', 'deskripsi', 'sumber', 'audio_terapis'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
