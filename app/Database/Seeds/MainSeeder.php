<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        // 1. Users
        $users = [
            [
                'username'     => 'admin',
                'email'        => 'admin@perpus.id',
                'nama_lengkap' => 'Administrator',
                'password'     => password_hash('admin123', PASSWORD_DEFAULT),
                'role'         => 'admin',
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'username'     => 'budi',
                'email'        => 'budi@mail.com',
                'nama_lengkap' => 'Budi Santoso',
                'password'     => password_hash('password', PASSWORD_DEFAULT),
                'role'         => 'user',
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'username'     => 'sari',
                'email'        => 'sari@mail.com',
                'nama_lengkap' => 'Sari Dewi',
                'password'     => password_hash('password', PASSWORD_DEFAULT),
                'role'         => 'user',
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'username'     => 'andi',
                'email'        => 'andi@mail.com',
                'nama_lengkap' => 'Andi Wijaya',
                'password'     => password_hash('password', PASSWORD_DEFAULT),
                'role'         => 'user',
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('users')->insertBatch($users);

        // 2. Anggota (id_user = 2,3,4)
        $anggota = [
            [
                'id_user'    => 2,
                'nim_nis'    => '2021001',
                'alamat'     => 'Jl. Merdeka No.1, Jakarta',
                'no_telp'    => '081234567890',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id_user'    => 3,
                'nim_nis'    => '2021002',
                'alamat'     => 'Jl. Sudirman No.5, Bandung',
                'no_telp'    => '082345678901',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id_user'    => 4,
                'nim_nis'    => '2021003',
                'alamat'     => 'Jl. Gatot Subroto No.3, Surabaya',
                'no_telp'    => '083456789012',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('anggota')->insertBatch($anggota);

        // 3. Kategori
        $kategori = [
            ['nama_kategori' => 'Teknologi', 'deskripsi' => 'Buku-buku tentang teknologi informasi dan komputer', 'created_at' => date('Y-m-d H:i:s')],
            ['nama_kategori' => 'Sastra',    'deskripsi' => 'Novel, puisi, cerpen, dan karya sastra lainnya', 'created_at' => date('Y-m-d H:i:s')],
            ['nama_kategori' => 'Sains',     'deskripsi' => 'Buku ilmu pengetahuan alam dan terapan', 'created_at' => date('Y-m-d H:i:s')],
            ['nama_kategori' => 'Sejarah',   'deskripsi' => 'Buku-buku tentang sejarah Indonesia dan dunia', 'created_at' => date('Y-m-d H:i:s')],
            ['nama_kategori' => 'Manajemen', 'deskripsi' => 'Buku manajemen bisnis dan kepemimpinan', 'created_at' => date('Y-m-d H:i:s')],
            ['nama_kategori' => 'Pendidikan','deskripsi' => 'Buku teks dan referensi pendidikan', 'created_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('kategori')->insertBatch($kategori);

        // 4. Buku
        $buku = [
            ['id_kategori' => 1, 'judul_buku' => 'Pemrograman PHP Modern', 'pengarang' => 'Ahmad Rosadi', 'penerbit' => 'Elex Media', 'tahun_terbit' => 2022, 'isbn' => '978-602-00-0001-1', 'deskripsi' => 'Panduan lengkap PHP 8 dengan praktik terbaik modern.', 'stok' => 5, 'dipinjam' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['id_kategori' => 1, 'judul_buku' => 'Belajar Laravel untuk Pemula', 'pengarang' => 'Bima Saputra', 'penerbit' => 'Informatika', 'tahun_terbit' => 2021, 'isbn' => '978-602-00-0002-2', 'deskripsi' => 'Tutorial Laravel dari dasar hingga deployment.', 'stok' => 3, 'dipinjam' => 0, 'created_at' => date('Y-m-d H:i:s')],
            ['id_kategori' => 1, 'judul_buku' => 'Pengantar Machine Learning', 'pengarang' => 'Citra Nugraha', 'penerbit' => 'Andi Publisher', 'tahun_terbit' => 2023, 'isbn' => '978-602-00-0003-3', 'deskripsi' => 'Memahami konsep dan implementasi machine learning.', 'stok' => 4, 'dipinjam' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['id_kategori' => 2, 'judul_buku' => 'Laskar Pelangi', 'pengarang' => 'Andrea Hirata', 'penerbit' => 'Bentang Pustaka', 'tahun_terbit' => 2005, 'isbn' => '978-602-00-0004-4', 'deskripsi' => 'Novel inspiratif tentang semangat anak-anak Belitung.', 'stok' => 6, 'dipinjam' => 2, 'created_at' => date('Y-m-d H:i:s')],
            ['id_kategori' => 2, 'judul_buku' => 'Bumi Manusia', 'pengarang' => 'Pramoedya Ananta Toer', 'penerbit' => 'Lentera Dipantara', 'tahun_terbit' => 1980, 'isbn' => '978-602-00-0005-5', 'deskripsi' => 'Novel sejarah yang menggambarkan masa kolonial.', 'stok' => 3, 'dipinjam' => 0, 'created_at' => date('Y-m-d H:i:s')],
            ['id_kategori' => 3, 'judul_buku' => 'Fisika Dasar Jilid 1', 'pengarang' => 'Halliday & Resnick', 'penerbit' => 'Erlangga', 'tahun_terbit' => 2019, 'isbn' => '978-602-00-0006-6', 'deskripsi' => 'Buku teks fisika universitas edisi terbaru.', 'stok' => 4, 'dipinjam' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['id_kategori' => 4, 'judul_buku' => 'Sejarah Indonesia Modern', 'pengarang' => 'Ricklefs', 'penerbit' => 'Gadjah Mada UP', 'tahun_terbit' => 2018, 'isbn' => '978-602-00-0007-7', 'deskripsi' => 'Sejarah Indonesia dari abad 18 hingga era reformasi.', 'stok' => 2, 'dipinjam' => 0, 'created_at' => date('Y-m-d H:i:s')],
            ['id_kategori' => 5, 'judul_buku' => 'Manajemen Strategis', 'pengarang' => 'David Fred', 'penerbit' => 'Salemba Empat', 'tahun_terbit' => 2020, 'isbn' => '978-602-00-0008-8', 'deskripsi' => 'Konsep manajemen strategis untuk era digital.', 'stok' => 5, 'dipinjam' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['id_kategori' => 1, 'judul_buku' => 'Docker & Kubernetes', 'pengarang' => 'Deni Pratama', 'penerbit' => 'InfoBook', 'tahun_terbit' => 2022, 'isbn' => '978-602-00-0009-9', 'deskripsi' => 'Panduan container dan orkestrasi untuk DevOps.', 'stok' => 3, 'dipinjam' => 0, 'created_at' => date('Y-m-d H:i:s')],
            ['id_kategori' => 6, 'judul_buku' => 'Psikologi Pendidikan', 'pengarang' => 'Santrock', 'penerbit' => 'Kencana', 'tahun_terbit' => 2017, 'isbn' => '978-602-00-0010-0', 'deskripsi' => 'Teori dan praktik psikologi dalam pendidikan.', 'stok' => 4, 'dipinjam' => 0, 'created_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('buku')->insertBatch($buku);

        // 5. Peminjaman (data diperbaiki dengan semua kolom lengkap)
        $peminjaman = [
            [
                'id_anggota'               => 1,
                'id_buku'                  => 1,
                'tanggal_pinjam'           => date('Y-m-d', strtotime('-5 days')),
                'tanggal_kembali_rencana'  => date('Y-m-d', strtotime('+2 days')),
                'tanggal_kembali_aktual'   => null,
                'status'                   => 'disetujui',
                'alasan_penolakan'         => null,
                'diproses_oleh'            => 1,
                'tanggal_proses'           => date('Y-m-d H:i:s'),
                'denda'                    => 0.00,
                'created_at'               => date('Y-m-d H:i:s'),
            ],
            [
                'id_anggota'               => 2,
                'id_buku'                  => 4,
                'tanggal_pinjam'           => date('Y-m-d', strtotime('-10 days')),
                'tanggal_kembali_rencana'  => date('Y-m-d', strtotime('-3 days')),
                'tanggal_kembali_aktual'   => null,
                'status'                   => 'disetujui',
                'alasan_penolakan'         => null,
                'diproses_oleh'            => 1,
                'tanggal_proses'           => date('Y-m-d H:i:s'),
                'denda'                    => 0.00,
                'created_at'               => date('Y-m-d H:i:s'),
            ],
            [
                'id_anggota'               => 3,
                'id_buku'                  => 3,
                'tanggal_pinjam'           => date('Y-m-d', strtotime('-2 days')),
                'tanggal_kembali_rencana'  => date('Y-m-d', strtotime('+5 days')),
                'tanggal_kembali_aktual'   => null,
                'status'                   => 'disetujui',
                'alasan_penolakan'         => null,
                'diproses_oleh'            => 1,
                'tanggal_proses'           => date('Y-m-d H:i:s'),
                'denda'                    => 0.00,
                'created_at'               => date('Y-m-d H:i:s'),
            ],
            [
                'id_anggota'               => 1,
                'id_buku'                  => 8,
                'tanggal_pinjam'           => date('Y-m-d', strtotime('-1 days')),
                'tanggal_kembali_rencana'  => date('Y-m-d', strtotime('+6 days')),
                'tanggal_kembali_aktual'   => null,
                'status'                   => 'pending',
                'alasan_penolakan'         => null,
                'diproses_oleh'            => null,
                'tanggal_proses'           => null,
                'denda'                    => 0.00,
                'created_at'               => date('Y-m-d H:i:s'),
            ],
            [
                'id_anggota'               => 2,
                'id_buku'                  => 6,
                'tanggal_pinjam'           => date('Y-m-d', strtotime('-15 days')),
                'tanggal_kembali_rencana'  => date('Y-m-d', strtotime('-8 days')),
                'tanggal_kembali_aktual'   => date('Y-m-d', strtotime('-8 days')),
                'status'                   => 'dikembalikan',
                'alasan_penolakan'         => null,
                'diproses_oleh'            => 1,
                'tanggal_proses'           => date('Y-m-d H:i:s'),
                'denda'                    => 0.00,
                'created_at'               => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('peminjaman')->insertBatch($peminjaman);

        // 6. Ulasan
        $ulasan = [
            ['id_buku' => 1, 'id_user' => 2, 'rating' => 5, 'komentar' => 'Buku yang sangat bagus dan mudah dipahami!', 'created_at' => date('Y-m-d H:i:s')],
            ['id_buku' => 4, 'id_user' => 3, 'rating' => 5, 'komentar' => 'Novel terbaik Indonesia sepanjang masa.', 'created_at' => date('Y-m-d H:i:s')],
            ['id_buku' => 3, 'id_user' => 4, 'rating' => 4, 'komentar' => 'Penjelasan machine learning sangat komprehensif.', 'created_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('ulasan')->insertBatch($ulasan);

        // 7. Notifikasi
        $notifikasi = [
            ['id_user' => 2, 'judul' => 'Peminjaman Disetujui', 'pesan' => 'Peminjaman buku "Pemrograman PHP Modern" telah disetujui.', 'link' => '/user/loans', 'is_read' => 0, 'created_at' => date('Y-m-d H:i:s')],
            ['id_user' => 3, 'judul' => 'Peminjaman Disetujui', 'pesan' => 'Peminjaman buku "Laskar Pelangi" telah disetujui.', 'link' => '/user/loans', 'is_read' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['id_user' => 1, 'judul' => 'Pengajuan Peminjaman Baru', 'pesan' => 'Budi Santoso mengajukan peminjaman buku "Manajemen Strategis".', 'link' => '/admin/loans', 'is_read' => 0, 'created_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('notifikasi')->insertBatch($notifikasi);
    }
}