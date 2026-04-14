<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $idAnggota = $this->session->get('id_anggota');
        $idUser    = $this->session->get('id_user');
        $db        = $this->db;

        $totalBuku    = $db->table('buku')->countAllResults();
        $totalPinjam  = $db->table('peminjaman')->where('id_anggota', $idAnggota)->countAllResults();
        $sedangPinjam = $db->table('peminjaman')->where('id_anggota', $idAnggota)->where('status', 'disetujui')->countAllResults();

        $notifications = $db->table('notifikasi')
            ->where('id_user', $idUser)->where('is_read', 0)
            ->orderBy('created_at', 'DESC')->limit(5)->get()->getResultArray();

        $rekomendasi = $db->table('buku b')
            ->select('b.*, COALESCE(AVG(u.rating),0) as avg_rating, COUNT(DISTINCT p.id_peminjaman) as total_pinjam')
            ->join('ulasan u', 'u.id_buku = b.id_buku', 'left')
            ->join('peminjaman p', 'p.id_buku = b.id_buku', 'left')
            ->where('b.stok > 0')
            ->groupBy('b.id_buku')
            ->orderBy('avg_rating', 'DESC')
            ->orderBy('total_pinjam', 'DESC')
            ->limit(8)->get()->getResultArray();

        return $this->userView('user/dashboard', compact(
            'totalBuku', 'totalPinjam', 'sedangPinjam', 'notifications', 'rekomendasi'
        ));
    }
}
