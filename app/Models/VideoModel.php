<?php

namespace App\Models;

use CodeIgniter\Model;

class VideoModel extends Model
{
    protected $table            = 'video';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['judul', 'deskripsi', 'sumber', 'video_terapis'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

}
