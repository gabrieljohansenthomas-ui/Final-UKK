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
        'id_kategori','judul_buku','pengarang','penerbit',
        'tahun_terbit','isbn','deskripsi','gambar','stok','dipinjam',
    ];

    public function getWithKategori(): array
    {
        return $this->db->table('buku b')
            ->select('b.*, k.nama_kategori,
                      COALESCE(AVG(u.rating),0) as avg_rating,
                      COUNT(DISTINCT u.id_ulasan) as total_ulasan,
                      COUNT(DISTINCT p.id_peminjaman) as total_pinjam')
            ->join('kategori k','k.id_kategori = b.id_kategori','left')
            ->join('ulasan u','u.id_buku = b.id_buku','left')
            ->join('peminjaman p','p.id_buku = b.id_buku','left')
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
            ->join('kategori k','k.id_kategori = b.id_kategori','left')
            ->join('ulasan u','u.id_buku = b.id_buku','left')
            ->join('peminjaman p','p.id_buku = b.id_buku','left')
            ->where('b.id_buku',$id)
            ->groupBy('b.id_buku')
            ->get()->getRowArray();
    }
}
