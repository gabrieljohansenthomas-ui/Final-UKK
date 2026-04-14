<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table         = 'buku';
    protected $primaryKey    = 'id_buku';
    protected $returnType    = 'array';
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    protected $allowedFields = [
        'id_kategori', 'judul_buku', 'pengarang', 'penerbit',
        'tahun_terbit', 'isbn', 'deskripsi', 'gambar', 'stok', 'dipinjam',
    ];

    public function getWithKategori()
    {
        return $this->db->table('buku b')
            ->select('b.*, k.nama_kategori, 
                      COALESCE(AVG(u.rating),0) as avg_rating,
                      COUNT(DISTINCT u.id_ulasan) as total_ulasan,
                      COUNT(DISTINCT p.id_peminjaman) as total_pinjam')
            ->join('kategori k', 'k.id_kategori = b.id_kategori', 'left')
            ->join('ulasan u', 'u.id_buku = b.id_buku', 'left')
            ->join('peminjaman p', 'p.id_buku = b.id_buku', 'left')
            ->groupBy('b.id_buku')
            ->get()->getResultArray();
    }

    public function getDetailWithRelations(int $id): ?array
    {
        return $this->db->table('buku b')
            ->select('b.*, k.nama_kategori,
                      COALESCE(AVG(u.rating),0) as avg_rating,
                      COUNT(DISTINCT u.id_ulasan) as total_ulasan,
                      COUNT(DISTINCT p.id_peminjaman) as total_pinjam')
            ->join('kategori k', 'k.id_kategori = b.id_kategori', 'left')
            ->join('ulasan u', 'u.id_buku = b.id_buku', 'left')
            ->join('peminjaman p', 'p.id_buku = b.id_buku', 'left')
            ->where('b.id_buku', $id)
            ->groupBy('b.id_buku')
            ->get()->getRowArray();
    }
}

// ---- KategoriModel ----
namespace App\Models;

class KategoriModel extends \CodeIgniter\Model
{
    protected $table         = 'kategori';
    protected $primaryKey    = 'id_kategori';
    protected $returnType    = 'array';
    protected $useTimestamps  = false;
    protected $allowedFields = ['nama_kategori', 'deskripsi', 'created_at'];
}

// ---- PeminjamanModel ----
namespace App\Models;

class PeminjamanModel extends \CodeIgniter\Model
{
    protected $table         = 'peminjaman';
    protected $primaryKey    = 'id_peminjaman';
    protected $returnType    = 'array';
    protected $useTimestamps  = false;
    protected $allowedFields = [
        'id_anggota', 'id_buku', 'tanggal_pinjam', 'tanggal_kembali_rencana',
        'tanggal_kembali_aktual', 'status', 'alasan_penolakan',
        'diproses_oleh', 'tanggal_proses', 'denda', 'created_at',
    ];

    public function getWithRelations(array $where = []): array
    {
        $builder = $this->db->table('peminjaman p')
            ->select('p.*, 
                      a.nim_nis,
                      u.nama_lengkap as nama_anggota, u.foto_profil,
                      b.judul_buku, b.gambar as gambar_buku, b.pengarang,
                      pu.nama_lengkap as nama_pemroses')
            ->join('anggota a', 'a.id_anggota = p.id_anggota', 'left')
            ->join('users u', 'u.id_user = a.id_user', 'left')
            ->join('buku b', 'b.id_buku = p.id_buku', 'left')
            ->join('users pu', 'pu.id_user = p.diproses_oleh', 'left')
            ->orderBy('p.created_at', 'DESC');

        foreach ($where as $key => $val) {
            $builder->where($key, $val);
        }
        return $builder->get()->getResultArray();
    }

    public function getDetail(int $id): ?array
    {
        return $this->db->table('peminjaman p')
            ->select('p.*, 
                      a.nim_nis, a.no_telp, a.alamat,
                      u.nama_lengkap as nama_anggota, u.email as email_anggota, u.foto_profil, u.id_user,
                      b.judul_buku, b.gambar as gambar_buku, b.pengarang, b.penerbit, b.stok,
                      pu.nama_lengkap as nama_pemroses')
            ->join('anggota a', 'a.id_anggota = p.id_anggota', 'left')
            ->join('users u', 'u.id_user = a.id_user', 'left')
            ->join('buku b', 'b.id_buku = p.id_buku', 'left')
            ->join('users pu', 'pu.id_user = p.diproses_oleh', 'left')
            ->where('p.id_peminjaman', $id)
            ->get()->getRowArray();
    }
}

// ---- NotifikasiModel ----
namespace App\Models;

class NotifikasiModel extends \CodeIgniter\Model
{
    protected $table         = 'notifikasi';
    protected $primaryKey    = 'id_notifikasi';
    protected $returnType    = 'array';
    protected $useTimestamps  = false;
    protected $allowedFields = ['id_user', 'judul', 'pesan', 'link', 'is_read', 'created_at'];
}

// ---- UlasanModel ----
namespace App\Models;

class UlasanModel extends \CodeIgniter\Model
{
    protected $table         = 'ulasan';
    protected $primaryKey    = 'id_ulasan';
    protected $returnType    = 'array';
    protected $useTimestamps  = false;
    protected $allowedFields = ['id_buku', 'id_user', 'rating', 'komentar', 'created_at'];

    public function getByBuku(int $idBuku): array
    {
        return $this->db->table('ulasan ul')
            ->select('ul.*, u.nama_lengkap, u.foto_profil')
            ->join('users u', 'u.id_user = ul.id_user', 'left')
            ->where('ul.id_buku', $idBuku)
            ->orderBy('ul.created_at', 'DESC')
            ->get()->getResultArray();
    }
}
