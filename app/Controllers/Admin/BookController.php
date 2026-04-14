<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BukuModel;
use App\Models\KategoriModel;

class BookController extends BaseController
{
    private BukuModel $bukuModel;
    private KategoriModel $kategoriModel;

    public function __construct()
    {
        $this->bukuModel    = new BukuModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $search    = $this->request->getGet('search') ?? '';
        $kategori  = $this->request->getGet('kategori') ?? '';

        $builder = $this->db->table('buku b')
            ->select('b.*, k.nama_kategori')
            ->join('kategori k', 'k.id_kategori = b.id_kategori', 'left')
            ->orderBy('b.id_buku', 'DESC');

        if ($search) {
            $builder->groupStart()
                ->like('b.judul_buku', $search)
                ->orLike('b.pengarang', $search)
                ->orLike('b.isbn', $search)
                ->groupEnd();
        }
        if ($kategori) {
            $builder->where('b.id_kategori', $kategori);
        }

        $books      = $builder->get()->getResultArray();
        $kategoris  = $this->kategoriModel->findAll();

        return $this->adminView('admin/books/index', compact('books', 'kategoris', 'search', 'kategori'));
    }

    public function create()
    {
        $kategoris = $this->kategoriModel->findAll();
        return $this->adminView('admin/books/form', compact('kategoris'));
    }

    public function store()
    {
        $rules = [
            'judul_buku'  => 'required|max_length[200]',
            'pengarang'   => 'required|max_length[150]',
            'isbn'        => 'permit_empty|is_unique[buku.isbn]',
            'stok'        => 'required|integer|greater_than_equal_to[0]',
            'gambar'      => 'permit_empty|uploaded[gambar]|max_size[gambar,2048]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/gif]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_kategori'  => $this->request->getPost('id_kategori') ?: null,
            'judul_buku'   => $this->request->getPost('judul_buku'),
            'pengarang'    => $this->request->getPost('pengarang'),
            'penerbit'     => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit') ?: null,
            'isbn'         => $this->request->getPost('isbn') ?: null,
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'stok'         => $this->request->getPost('stok'),
            'dipinjam'     => 0,
            'gambar'       => 'no-cover.png',
        ];

        // Upload gambar
        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/covers', $newName);
            $data['gambar'] = $newName;
        }

        $this->bukuModel->insert($data);
        return redirect()->to('/admin/books')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $book      = $this->bukuModel->find($id);
        $kategoris = $this->kategoriModel->findAll();
        if (!$book) return redirect()->to('/admin/books')->with('error', 'Buku tidak ditemukan.');
        return $this->adminView('admin/books/form', compact('book', 'kategoris'));
    }

    public function update(int $id)
    {
        $book = $this->bukuModel->find($id);
        if (!$book) return redirect()->to('/admin/books')->with('error', 'Buku tidak ditemukan.');

        $rules = [
            'judul_buku' => 'required|max_length[200]',
            'pengarang'  => 'required|max_length[150]',
            'isbn'       => "permit_empty|is_unique[buku.isbn,id_buku,$id]",
            'stok'       => 'required|integer|greater_than_equal_to[0]',
            'gambar'     => 'permit_empty|uploaded[gambar]|max_size[gambar,2048]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/gif]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_kategori'  => $this->request->getPost('id_kategori') ?: null,
            'judul_buku'   => $this->request->getPost('judul_buku'),
            'pengarang'    => $this->request->getPost('pengarang'),
            'penerbit'     => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit') ?: null,
            'isbn'         => $this->request->getPost('isbn') ?: null,
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'stok'         => $this->request->getPost('stok'),
        ];

        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Hapus gambar lama
            if ($book['gambar'] && $book['gambar'] !== 'no-cover.png') {
                $oldPath = ROOTPATH . 'public/uploads/covers/' . $book['gambar'];
                if (file_exists($oldPath)) unlink($oldPath);
            }
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/covers', $newName);
            $data['gambar'] = $newName;
        }

        $this->bukuModel->update($id, $data);
        return redirect()->to('/admin/books')->with('success', 'Buku berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $book = $this->bukuModel->find($id);
        if (!$book) return redirect()->to('/admin/books')->with('error', 'Buku tidak ditemukan.');

        if ($book['gambar'] && $book['gambar'] !== 'no-cover.png') {
            $path = ROOTPATH . 'public/uploads/covers/' . $book['gambar'];
            if (file_exists($path)) unlink($path);
        }

        $this->bukuModel->delete($id);
        return redirect()->to('/admin/books')->with('success', 'Buku berhasil dihapus.');
    }
}
