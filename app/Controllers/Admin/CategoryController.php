<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriModel;

class CategoryController extends BaseController
{
    public function index()
    {
        $model = new KategoriModel();
        $kategoris = $this->db->table('kategori k')
            ->select('k.*, COUNT(b.id_buku) as jumlah_buku')
            ->join('buku b', 'b.id_kategori = k.id_kategori', 'left')
            ->groupBy('k.id_kategori')
            ->orderBy('k.id_kategori', 'DESC')
            ->get()->getResultArray();

        return $this->adminView('admin/categories/index', compact('kategoris'));
    }

    public function store()
    {
        $rules = [
            'nama_kategori' => 'required|max_length[100]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $model = new KategoriModel();
        $model->insert([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'created_at'    => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(int $id)
    {
        $rules = ['nama_kategori' => 'required|max_length[100]'];
        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $model = new KategoriModel();
        $model->update($id, [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
        ]);
        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $used = $this->db->table('buku')->where('id_kategori', $id)->countAllResults();
        if ($used > 0) {
            return redirect()->to('/admin/categories')->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh buku.');
        }
        $model = new KategoriModel();
        $model->delete($id);
        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil dihapus.');
    }
}
