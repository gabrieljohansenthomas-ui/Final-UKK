<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table         = 'anggota';
    protected $primaryKey    = 'id_anggota';
    protected $returnType    = 'array';
    protected $useTimestamps  = false;

    protected $allowedFields = [
        'id_user', 'nim_nis', 'alamat', 'no_telp', 'foto_ktp', 'created_at',
    ];
}
