<?php
namespace App\Models;
use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table         = 'peminjaman';
    protected $primaryKey    = 'id_peminjaman';
    protected $returnType    = 'array';
    protected $useTimestamps  = false;
    protected $allowedFields = [
        'id_anggota','id_buku','tanggal_pinjam','tanggal_kembali_rencana',
        'tanggal_kembali_aktual','status','alasan_penolakan',
        'diproses_oleh','tanggal_proses','denda','created_at',
    ];

    public function getWithRelations(array $where = []): array
    {
        $builder = $this->db->table('peminjaman p')
            ->select('p.*, a.nim_nis,
                      u.nama_lengkap as nama_anggota, u.foto_profil,
                      b.judul_buku, b.gambar as gambar_buku, b.pengarang,
                      pu.nama_lengkap as nama_pemroses')
            ->join('anggota a','a.id_anggota = p.id_anggota','left')
            ->join('users u','u.id_user = a.id_user','left')
            ->join('buku b','b.id_buku = p.id_buku','left')
            ->join('users pu','pu.id_user = p.diproses_oleh','left')
            ->orderBy('p.created_at','DESC');
        foreach ($where as $k => $v) $builder->where($k, $v);
        return $builder->get()->getResultArray();
    }

    public function getDetail(int $id): ?array
    {
        return $this->db->table('peminjaman p')
            ->select('p.*, a.nim_nis, a.no_telp, a.alamat,
                      u.nama_lengkap as nama_anggota, u.email as email_anggota,
                      u.foto_profil, u.id_user,
                      b.judul_buku, b.gambar as gambar_buku, b.pengarang, b.penerbit, b.stok,
                      pu.nama_lengkap as nama_pemroses')
            ->join('anggota a','a.id_anggota = p.id_anggota','left')
            ->join('users u','u.id_user = a.id_user','left')
            ->join('buku b','b.id_buku = p.id_buku','left')
            ->join('users pu','pu.id_user = p.diproses_oleh','left')
            ->where('p.id_peminjaman',$id)
            ->get()->getRowArray();
    }
}
