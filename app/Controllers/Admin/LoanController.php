<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\BukuModel;

class LoanController extends BaseController
{
    public function index()
    {
        $status = $this->request->getGet('status') ?? '';
        $search = $this->request->getGet('search') ?? '';

        $builder = $this->db->table('peminjaman p')
            ->select('p.*, u.nama_lengkap as nama_anggota, b.judul_buku, b.pengarang')
            ->join('anggota a', 'a.id_anggota = p.id_anggota', 'left')
            ->join('users u', 'u.id_user = a.id_user', 'left')
            ->join('buku b', 'b.id_buku = p.id_buku', 'left')
            ->orderBy('p.created_at', 'DESC');

        if ($status) $builder->where('p.status', $status);
        if ($search) {
            $builder->groupStart()
                ->like('u.nama_lengkap', $search)
                ->orLike('b.judul_buku', $search)
                ->groupEnd();
        }

        $loans = $builder->get()->getResultArray();
        return $this->adminView('admin/loans/index', compact('loans', 'status', 'search'));
    }

    public function detail(int $id)
    {
        $peminjamanModel = new PeminjamanModel();
        $loan = $peminjamanModel->getDetail($id);
        if (!$loan) return redirect()->to('/admin/loans')->with('error', 'Peminjaman tidak ditemukan.');
        return $this->adminView('admin/loans/detail', compact('loan'));
    }

    public function approve(int $id)
    {
        $peminjamanModel = new PeminjamanModel();
        $loan = $peminjamanModel->find($id);
        if (!$loan || $loan['status'] !== 'pending') {
            return redirect()->to('/admin/loans')->with('error', 'Peminjaman tidak bisa diproses.');
        }

        // Update stok buku
        $bukuModel = new BukuModel();
        $buku = $bukuModel->find($loan['id_buku']);
        if ($buku['stok'] - $buku['dipinjam'] <= 0) {
            return redirect()->to('/admin/loans')->with('error', 'Stok buku tidak tersedia.');
        }

        $bukuModel->update($loan['id_buku'], [
            'dipinjam' => $buku['dipinjam'] + 1,
        ]);

        $peminjamanModel->update($id, [
            'status'         => 'disetujui',
            'diproses_oleh'  => $this->session->get('id_user'),
            'tanggal_proses' => date('Y-m-d H:i:s'),
        ]);

        // Notifikasi ke user
        $anggota = $this->db->table('anggota')->select('id_user')->where('id_anggota', $loan['id_anggota'])->get()->getRowArray();
        if ($anggota) {
            $this->sendNotification(
                $anggota['id_user'],
                'Peminjaman Disetujui',
                "Peminjaman buku \"{$buku['judul_buku']}\" telah disetujui. Silakan ambil buku.",
                '/user/loans'
            );
        }

        return redirect()->to('/admin/loans')->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject(int $id)
    {
        $alasan = $this->request->getPost('alasan_penolakan');
        if (!$alasan) {
            return redirect()->back()->with('error', 'Alasan penolakan wajib diisi.');
        }

        $peminjamanModel = new PeminjamanModel();
        $loan = $peminjamanModel->find($id);
        if (!$loan || $loan['status'] !== 'pending') {
            return redirect()->to('/admin/loans')->with('error', 'Peminjaman tidak bisa diproses.');
        }

        $peminjamanModel->update($id, [
            'status'            => 'ditolak',
            'alasan_penolakan'  => $alasan,
            'diproses_oleh'     => $this->session->get('id_user'),
            'tanggal_proses'    => date('Y-m-d H:i:s'),
        ]);

        $buku = $this->db->table('buku')->where('id_buku', $loan['id_buku'])->get()->getRowArray();
        $anggota = $this->db->table('anggota')->select('id_user')->where('id_anggota', $loan['id_anggota'])->get()->getRowArray();
        if ($anggota && $buku) {
            $this->sendNotification(
                $anggota['id_user'],
                'Peminjaman Ditolak',
                "Peminjaman buku \"{$buku['judul_buku']}\" ditolak. Alasan: $alasan",
                '/user/loans'
            );
        }

        return redirect()->to('/admin/loans')->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function returnBook(int $id)
    {
        $peminjamanModel = new PeminjamanModel();
        $loan = $peminjamanModel->find($id);
        if (!$loan || $loan['status'] !== 'disetujui') {
            return redirect()->to('/admin/loans')->with('error', 'Peminjaman tidak bisa dikembalikan.');
        }

        $today = date('Y-m-d');
        $rencana = $loan['tanggal_kembali_rencana'];

        // Hitung denda
        $denda = 0;
        if ($today > $rencana) {
            $diff = (strtotime($today) - strtotime($rencana)) / 86400;
            $denda = ceil($diff) * 1000; // Rp1000/hari
        }

        $bukuModel = new BukuModel();
        $buku = $bukuModel->find($loan['id_buku']);
        $bukuModel->update($loan['id_buku'], [
            'dipinjam' => max(0, $buku['dipinjam'] - 1),
        ]);

        $peminjamanModel->update($id, [
            'status'                  => 'dikembalikan',
            'tanggal_kembali_aktual'  => $today,
            'denda'                   => $denda,
            'diproses_oleh'           => $this->session->get('id_user'),
            'tanggal_proses'          => date('Y-m-d H:i:s'),
        ]);

        $anggota = $this->db->table('anggota')->select('id_user')->where('id_anggota', $loan['id_anggota'])->get()->getRowArray();
        if ($anggota) {
            $dendaMsg = $denda > 0 ? " Denda: Rp " . number_format($denda, 0, ',', '.') . "." : "";
            $this->sendNotification(
                $anggota['id_user'],
                'Buku Dikembalikan',
                "Buku \"{$buku['judul_buku']}\" berhasil dikembalikan.$dendaMsg",
                '/user/loans'
            );
        }

        return redirect()->to('/admin/loans')->with('success', 'Buku berhasil dikembalikan.' . ($denda > 0 ? " Denda: Rp " . number_format($denda) : ''));
    }

    public function delete(int $id)
    {
        $peminjamanModel = new PeminjamanModel();
        $peminjamanModel->delete($id);
        return redirect()->to('/admin/loans')->with('success', 'Data peminjaman dihapus.');
    }
}
