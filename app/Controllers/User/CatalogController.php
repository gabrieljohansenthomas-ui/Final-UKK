<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\BukuModel;
use App\Models\KategoriModel;
use App\Models\UlasanModel;

class CatalogController extends BaseController
{
    public function index()
    {
        $search   = $this->request->getGet('search') ?? '';
        $kategori = $this->request->getGet('kategori') ?? '';

        $builder = $this->db->table('buku b')
            ->select('b.*, k.nama_kategori, COALESCE(AVG(u.rating),0) as avg_rating, COUNT(DISTINCT u.id_ulasan) as total_ulasan')
            ->join('kategori k', 'k.id_kategori = b.id_kategori', 'left')
            ->join('ulasan u', 'u.id_buku = b.id_buku', 'left')
            ->groupBy('b.id_buku')
            ->orderBy('b.judul_buku', 'ASC');

        if ($search) {
            $builder->groupStart()
                ->like('b.judul_buku', $search)
                ->orLike('b.pengarang', $search)
                ->groupEnd();
        }
        if ($kategori) $builder->where('b.id_kategori', $kategori);

        $books     = $builder->get()->getResultArray();
        $kategoris = (new KategoriModel())->findAll();

        return $this->userView('user/catalog/index', compact('books', 'kategoris', 'search', 'kategori'));
    }

    public function detail(int $id)
    {
        $bukuModel = new BukuModel();
        $book      = $bukuModel->getDetailWithRelations($id);
        if (!$book) return redirect()->to('/user/catalog')->with('error', 'Buku tidak ditemukan.');

        $ulasanModel = new UlasanModel();
        $ulasanList  = $ulasanModel->getByBuku($id);
        $myUlasan    = $ulasanModel->where('id_buku', $id)->where('id_user', $this->session->get('id_user'))->first();

        return $this->userView('user/catalog/detail', compact('book', 'ulasanList', 'myUlasan'));
    }

    public function submitReview(int $idBuku)
    {
        $rules = [
            'rating'   => 'required|integer|in_list[1,2,3,4,5]',
            'komentar' => 'permit_empty|max_length[500]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $ulasanModel = new UlasanModel();
        $idUser      = $this->session->get('id_user');
        $existing    = $ulasanModel->where('id_buku', $idBuku)->where('id_user', $idUser)->first();

        $data = [
            'id_buku'   => $idBuku,
            'id_user'   => $idUser,
            'rating'    => $this->request->getPost('rating'),
            'komentar'  => $this->request->getPost('komentar'),
            'created_at'=> date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $ulasanModel->update($existing['id_ulasan'], $data);
            $msg = 'Ulasan berhasil diperbarui.';
        } else {
            $ulasanModel->insert($data);
            $msg = 'Ulasan berhasil dikirim.';
        }

        return redirect()->to('/user/catalog/detail/' . $idBuku)->with('success', $msg);
    }
}
