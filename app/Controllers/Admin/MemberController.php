<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class MemberController extends BaseController
{
    public function index()
    {
        $search = $this->request->getGet('search') ?? '';

        $builder = $this->db->table('anggota a')
            ->select('a.*, u.nama_lengkap, u.username, u.email, u.foto_profil, u.status')
            ->join('users u', 'u.id_user = a.id_user', 'left')
            ->orderBy('a.id_anggota', 'DESC');

        if ($search) {
            $builder->groupStart()
                ->like('u.nama_lengkap', $search)
                ->orLike('u.username', $search)
                ->orLike('u.email', $search)
                ->orLike('a.nim_nis', $search)
                ->groupEnd();
        }

        $members = $builder->get()->getResultArray();
        return $this->adminView('admin/members/index', compact('members', 'search'));
    }

    public function detail(int $id)
    {
        $member = $this->db->table('anggota a')
            ->select('a.*, u.nama_lengkap, u.username, u.email, u.foto_profil, u.role, u.status, u.last_login, u.created_at as tgl_daftar')
            ->join('users u', 'u.id_user = a.id_user', 'left')
            ->where('a.id_anggota', $id)
            ->get()->getRowArray();

        if (!$member) return redirect()->to('/admin/members')->with('error', 'Anggota tidak ditemukan.');

        $loans = $this->db->table('peminjaman p')
            ->select('p.*, b.judul_buku, b.pengarang')
            ->join('buku b', 'b.id_buku = p.id_buku', 'left')
            ->where('p.id_anggota', $id)
            ->orderBy('p.created_at', 'DESC')
            ->get()->getResultArray();

        return $this->adminView('admin/members/detail', compact('member', 'loans'));
    }
}
