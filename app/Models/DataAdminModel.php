<?php

namespace App\Models;

use CodeIgniter\Model;

class DataAdminModel extends Model
{
    protected $table = 'data_admin';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'email', 'password'];
}
