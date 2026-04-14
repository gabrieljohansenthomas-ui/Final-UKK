<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ReportController extends BaseController
{
    public function index()
    {
        $db = $this->db;

        $totalPinjam = $db->table('peminjaman')->countAllResults();
        $totalDenda  = $db->query("SELECT COALESCE(SUM(denda),0) as total FROM peminjaman WHERE status='dikembalikan'")->getRow()->total;

        $popularBooks = $db->table('peminjaman p')
            ->select('b.judul_buku, b.pengarang, b.gambar, COUNT(p.id_peminjaman) as total')
            ->join('buku b', 'b.id_buku = p.id_buku')
            ->groupBy('p.id_buku')
            ->orderBy('total', 'DESC')
            ->limit(10)->get()->getResultArray();

        $activeMembers = $db->table('peminjaman p')
            ->select('u.nama_lengkap, u.email, u.foto_profil, COUNT(p.id_peminjaman) as total')
            ->join('anggota a', 'a.id_anggota = p.id_anggota', 'left')
            ->join('users u', 'u.id_user = a.id_user', 'left')
            ->groupBy('p.id_anggota')
            ->orderBy('total', 'DESC')
            ->limit(10)->get()->getResultArray();

        return $this->adminView('admin/reports/index', compact('totalPinjam', 'totalDenda', 'popularBooks', 'activeMembers'));
    }

    public function exportPdf()
    {
        // Placeholder demo
        return redirect()->to('/admin/reports')->with('info', 'Fitur export PDF (placeholder untuk demo).');
    }

    public function exportExcel()
    {
        // Placeholder demo
        return redirect()->to('/admin/reports')->with('info', 'Fitur export Excel (placeholder untuk demo).');
    }
}
