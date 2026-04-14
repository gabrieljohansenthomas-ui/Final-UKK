<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\BukuModel;

class LoanController extends BaseController
{
    public function index()
    {
        $idAnggota = $this->session->get('id_anggota');
        $loans = $this->db->table('peminjaman p')
            ->select('p.*, b.judul_buku, b.pengarang, b.gambar as gambar_buku')
            ->join('buku b', 'b.id_buku = p.id_buku', 'left')
            ->where('p.id_anggota', $idAnggota)
            ->orderBy('p.created_at', 'DESC')
            ->get()->getResultArray();

        return $this->userView('user/loans/index', compact('loans'));
    }

    public function create(int $idBuku)
    {
        $bukuModel = new BukuModel();
        $book = $bukuModel->find($idBuku);
        if (!$book) return redirect()->to('/user/catalog')->with('error', 'Buku tidak ditemukan.');
        if (($book['stok'] - $book['dipinjam']) <= 0) {
            return redirect()->to('/user/catalog/detail/' . $idBuku)->with('error', 'Stok buku tidak tersedia.');
        }

        // Cek apakah user sudah meminjam buku ini (status pending/disetujui)
        $existing = $this->db->table('peminjaman')
            ->where('id_anggota', $this->session->get('id_anggota'))
            ->where('id_buku', $idBuku)
            ->whereIn('status', ['pending', 'disetujui'])
            ->countAllResults();

        if ($existing > 0) {
            return redirect()->to('/user/catalog/detail/' . $idBuku)->with('error', 'Anda sudah meminjam atau mengajukan peminjaman buku ini.');
        }

        return $this->userView('user/loans/create', compact('book'));
    }

    public function store()
    {
        $rules = [
            'id_buku'                 => 'required|integer',
            'tanggal_pinjam'          => 'required|valid_date',
            'tanggal_kembali_rencana' => 'required|valid_date',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $idBuku    = $this->request->getPost('id_buku');
        $idAnggota = $this->session->get('id_anggota');

        $peminjamanModel = new PeminjamanModel();
        $peminjamanModel->insert([
            'id_anggota'              => $idAnggota,
            'id_buku'                 => $idBuku,
            'tanggal_pinjam'          => $this->request->getPost('tanggal_pinjam'),
            'tanggal_kembali_rencana' => $this->request->getPost('tanggal_kembali_rencana'),
            'status'                  => 'pending',
            'created_at'              => date('Y-m-d H:i:s'),
        ]);

        // Notifikasi ke admin (id_user = 1)
        $buku = (new BukuModel())->find($idBuku);
        $namaUser = $this->session->get('nama_lengkap');
        $this->sendNotification(1, 'Pengajuan Peminjaman Baru',
            "$namaUser mengajukan peminjaman buku \"{$buku['judul_buku']}\".",
            '/admin/loans'
        );

        return redirect()->to('/user/loans')->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan admin.');
    }
}
