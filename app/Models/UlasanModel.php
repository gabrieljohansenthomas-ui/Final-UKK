<?php
namespace App\Models;
use CodeIgniter\Model;

class UlasanModel extends Model
{
    protected $table         = 'ulasan';
    protected $primaryKey    = 'id_ulasan';
    protected $returnType    = 'array';
    protected $useTimestamps  = false;
    protected $allowedFields = ['id_buku','id_user','rating','komentar','created_at'];

    public function getByBuku(int $idBuku): array
    {
        return $this->db->table('ulasan ul')
            ->select('ul.*, u.nama_lengkap, u.foto_profil')
            ->join('users u','u.id_user = ul.id_user','left')
            ->where('ul.id_buku',$idBuku)
            ->orderBy('ul.created_at','DESC')
            ->get()->getResultArray();
    }
}
