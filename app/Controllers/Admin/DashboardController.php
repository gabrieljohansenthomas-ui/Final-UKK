<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $db = $this->db;

        // Statistik utama
        $totalBuku     = $db->table('buku')->countAllResults();
        $totalAnggota  = $db->table('anggota')->countAllResults();
        $totalPinjam   = $db->table('peminjaman')->countAllResults();
        $pending       = $db->table('peminjaman')->where('status', 'pending')->countAllResults();
        $terlambat     = $db->table('peminjaman')
            ->where('status', 'disetujui')
            ->where('tanggal_kembali_rencana <', date('Y-m-d'))
            ->countAllResults();

        // Grafik: peminjaman per bulan (12 bulan terakhir)
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $count = $db->query(
                "SELECT COUNT(*) as c FROM peminjaman WHERE DATE_FORMAT(created_at,'%Y-%m') = ?",
                [$month]
            )->getRow()->c;
            $monthlyData[] = ['label' => date('M Y', strtotime("-$i months")), 'count' => (int)$count];
        }

        // Buku populer
        $popularBooks = $db->table('peminjaman p')
            ->select('b.judul_buku, b.pengarang, b.gambar, COUNT(p.id_peminjaman) as total')
            ->join('buku b', 'b.id_buku = p.id_buku')
            ->groupBy('p.id_buku')
            ->orderBy('total', 'DESC')
            ->limit(5)->get()->getResultArray();

        // 5 peminjaman terbaru
        $recentLoans = $db->table('peminjaman p')
            ->select('p.id_peminjaman, p.tanggal_pinjam, p.status, u.nama_lengkap, b.judul_buku')
            ->join('anggota a', 'a.id_anggota = p.id_anggota', 'left')
            ->join('users u', 'u.id_user = a.id_user', 'left')
            ->join('buku b', 'b.id_buku = p.id_buku', 'left')
            ->orderBy('p.created_at', 'DESC')
            ->limit(5)->get()->getResultArray();

        return $this->adminView('admin/dashboard', compact(
            'totalBuku', 'totalAnggota', 'totalPinjam', 'pending', 'terlambat',
            'monthlyData', 'popularBooks', 'recentLoans'
        ));
    }
}
