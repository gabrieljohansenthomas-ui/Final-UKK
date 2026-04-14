-- ============================================================
-- PERPUSTAKAAN DIGITAL - DATABASE SCHEMA
-- ============================================================

CREATE DATABASE IF NOT EXISTS perpus_digital CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE perpus_digital;

-- ------------------------------------------------------------
-- Tabel: users
-- ------------------------------------------------------------
CREATE TABLE users (
    id_user       INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    email         VARCHAR(100) NOT NULL UNIQUE,
    nama_lengkap  VARCHAR(100) NOT NULL,
    password      VARCHAR(255) NOT NULL,
    foto_profil   VARCHAR(255) DEFAULT 'default.png',
    role          ENUM('admin','user') NOT NULL DEFAULT 'user',
    status        TINYINT(1)   NOT NULL DEFAULT 1 COMMENT '1=aktif, 0=nonaktif',
    last_login    DATETIME     DEFAULT NULL,
    created_at    DATETIME     DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel: anggota
-- ------------------------------------------------------------
CREATE TABLE anggota (
    id_anggota  INT AUTO_INCREMENT PRIMARY KEY,
    id_user     INT          NOT NULL UNIQUE,
    nim_nis     VARCHAR(50)  DEFAULT NULL,
    alamat      TEXT         DEFAULT NULL,
    no_telp     VARCHAR(20)  DEFAULT NULL,
    foto_ktp    VARCHAR(255) DEFAULT NULL,
    created_at  DATETIME     DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_anggota_user FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel: kategori
-- ------------------------------------------------------------
CREATE TABLE kategori (
    id_kategori    INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori  VARCHAR(100) NOT NULL,
    deskripsi      TEXT         DEFAULT NULL,
    created_at     DATETIME     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel: buku
-- ------------------------------------------------------------
CREATE TABLE buku (
    id_buku        INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori    INT          DEFAULT NULL,
    judul_buku     VARCHAR(200) NOT NULL,
    pengarang      VARCHAR(150) NOT NULL,
    penerbit       VARCHAR(150) DEFAULT NULL,
    tahun_terbit   YEAR         DEFAULT NULL,
    isbn           VARCHAR(20)  DEFAULT NULL UNIQUE,
    deskripsi      TEXT         DEFAULT NULL,
    gambar         VARCHAR(255) DEFAULT 'no-cover.png',
    stok           INT          NOT NULL DEFAULT 1,
    dipinjam       INT          NOT NULL DEFAULT 0,
    created_at     DATETIME     DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_buku_kategori FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel: peminjaman
-- ------------------------------------------------------------
CREATE TABLE peminjaman (
    id_peminjaman           INT AUTO_INCREMENT PRIMARY KEY,
    id_anggota              INT          NOT NULL,
    id_buku                 INT          NOT NULL,
    tanggal_pinjam          DATE         NOT NULL,
    tanggal_kembali_rencana DATE         NOT NULL,
    tanggal_kembali_aktual  DATE         DEFAULT NULL,
    status                  ENUM('pending','disetujui','ditolak','dikembalikan') NOT NULL DEFAULT 'pending',
    alasan_penolakan        TEXT         DEFAULT NULL,
    diproses_oleh           INT          DEFAULT NULL,
    tanggal_proses          DATETIME     DEFAULT NULL,
    denda                   DECIMAL(10,2) DEFAULT 0.00,
    created_at              DATETIME     DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pinjam_anggota  FOREIGN KEY (id_anggota)    REFERENCES anggota(id_anggota),
    CONSTRAINT fk_pinjam_buku     FOREIGN KEY (id_buku)       REFERENCES buku(id_buku),
    CONSTRAINT fk_pinjam_proses   FOREIGN KEY (diproses_oleh) REFERENCES users(id_user) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel: notifikasi
-- ------------------------------------------------------------
CREATE TABLE notifikasi (
    id_notifikasi  INT AUTO_INCREMENT PRIMARY KEY,
    id_user        INT          NOT NULL,
    judul          VARCHAR(200) NOT NULL,
    pesan          TEXT         NOT NULL,
    link           VARCHAR(255) DEFAULT NULL,
    is_read        TINYINT(1)   NOT NULL DEFAULT 0,
    created_at     DATETIME     DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notif_user FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel: ulasan
-- ------------------------------------------------------------
CREATE TABLE ulasan (
    id_ulasan   INT AUTO_INCREMENT PRIMARY KEY,
    id_buku     INT          NOT NULL,
    id_user     INT          NOT NULL,
    rating      TINYINT      NOT NULL CHECK (rating BETWEEN 1 AND 5),
    komentar    TEXT         DEFAULT NULL,
    created_at  DATETIME     DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_ulasan (id_buku, id_user),
    CONSTRAINT fk_ulasan_buku FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE,
    CONSTRAINT fk_ulasan_user FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- SEED DATA
-- ============================================================

-- Admin default (password: admin123)
INSERT INTO users (username, email, nama_lengkap, password, role, status) VALUES
('admin', 'admin@perpus.id', 'Administrator', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1),
('budi', 'budi@mail.com', 'Budi Santoso', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 1),
('sari', 'sari@mail.com', 'Sari Dewi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 1),
('andi', 'andi@mail.com', 'Andi Wijaya', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 1);

INSERT INTO anggota (id_user, nim_nis, alamat, no_telp) VALUES
(2, '2021001', 'Jl. Merdeka No.1, Jakarta', '081234567890'),
(3, '2021002', 'Jl. Sudirman No.5, Bandung', '082345678901'),
(4, '2021003', 'Jl. Gatot Subroto No.3, Surabaya', '083456789012');

INSERT INTO kategori (nama_kategori, deskripsi) VALUES
('Teknologi', 'Buku-buku tentang teknologi informasi dan komputer'),
('Sastra', 'Novel, puisi, cerpen, dan karya sastra lainnya'),
('Sains', 'Buku ilmu pengetahuan alam dan terapan'),
('Sejarah', 'Buku-buku tentang sejarah Indonesia dan dunia'),
('Manajemen', 'Buku manajemen bisnis dan kepemimpinan'),
('Pendidikan', 'Buku teks dan referensi pendidikan');

INSERT INTO buku (id_kategori, judul_buku, pengarang, penerbit, tahun_terbit, isbn, deskripsi, stok, dipinjam) VALUES
(1, 'Pemrograman PHP Modern', 'Ahmad Rosadi', 'Elex Media', 2022, '978-602-00-0001-1', 'Panduan lengkap PHP 8 dengan praktik terbaik modern.', 5, 1),
(1, 'Belajar Laravel untuk Pemula', 'Bima Saputra', 'Informatika', 2021, '978-602-00-0002-2', 'Tutorial Laravel dari dasar hingga deployment.', 3, 0),
(1, 'Pengantar Machine Learning', 'Citra Nugraha', 'Andi Publisher', 2023, '978-602-00-0003-3', 'Memahami konsep dan implementasi machine learning.', 4, 1),
(2, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, '978-602-00-0004-4', 'Novel inspiratif tentang semangat anak-anak Belitung.', 6, 2),
(2, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Lentera Dipantara', 1980, '978-602-00-0005-5', 'Novel sejarah yang menggambarkan masa kolonial.', 3, 0),
(3, 'Fisika Dasar Jilid 1', 'Halliday & Resnick', 'Erlangga', 2019, '978-602-00-0006-6', 'Buku teks fisika universitas edisi terbaru.', 4, 1),
(4, 'Sejarah Indonesia Modern', 'Ricklefs', 'Gadjah Mada UP', 2018, '978-602-00-0007-7', 'Sejarah Indonesia dari abad 18 hingga era reformasi.', 2, 0),
(5, 'Manajemen Strategis', 'David Fred', 'Salemba Empat', 2020, '978-602-00-0008-8', 'Konsep manajemen strategis untuk era digital.', 5, 1),
(1, 'Docker & Kubernetes', 'Deni Pratama', 'InfoBook', 2022, '978-602-00-0009-9', 'Panduan container dan orkestrasi untuk DevOps.', 3, 0),
(6, 'Psikologi Pendidikan', 'Santrock', 'Kencana', 2017, '978-602-00-0010-0', 'Teori dan praktik psikologi dalam pendidikan.', 4, 0);

-- Peminjaman sample
INSERT INTO peminjaman (id_anggota, id_buku, tanggal_pinjam, tanggal_kembali_rencana, status, diproses_oleh, tanggal_proses) VALUES
(1, 1, DATE_SUB(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'disetujui', 1, NOW()),
(2, 4, DATE_SUB(CURDATE(), INTERVAL 10 DAY), DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'disetujui', 1, NOW()),
(3, 3, DATE_SUB(CURDATE(), INTERVAL 2 DAY), DATE_ADD(CURDATE(), INTERVAL 5 DAY), 'disetujui', 1, NOW()),
(1, 8, DATE_SUB(CURDATE(), INTERVAL 1 DAY), DATE_ADD(CURDATE(), INTERVAL 6 DAY), 'pending', NULL, NULL),
(2, 6, DATE_SUB(CURDATE(), INTERVAL 15 DAY), DATE_SUB(CURDATE(), INTERVAL 8 DAY), 'dikembalikan', 1, NOW());

-- Ulasan sample
INSERT INTO ulasan (id_buku, id_user, rating, komentar) VALUES
(1, 2, 5, 'Buku yang sangat bagus dan mudah dipahami!'),
(4, 3, 5, 'Novel terbaik Indonesia sepanjang masa.'),
(3, 4, 4, 'Penjelasan machine learning sangat komprehensif.');

-- Notifikasi sample
INSERT INTO notifikasi (id_user, judul, pesan, link, is_read) VALUES
(2, 'Peminjaman Disetujui', 'Peminjaman buku "Pemrograman PHP Modern" telah disetujui.', '/user/loans', 0),
(3, 'Peminjaman Disetujui', 'Peminjaman buku "Laskar Pelangi" telah disetujui.', '/user/loans', 1),
(1, 'Pengajuan Peminjaman Baru', 'Budi Santoso mengajukan peminjaman buku "Manajemen Strategis".', '/admin/loans', 0);
