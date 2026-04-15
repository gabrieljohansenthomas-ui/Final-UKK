# 📚 Perpustakaan Digital — CodeIgniter 4

Aplikasi manajemen perpustakaan digital berbasis **CodeIgniter 4** dengan fitur peminjaman buku, manajemen anggota, kategori, ulasan, notifikasi, dan laporan. Mendukung dua role pengguna: **Admin** dan **User (Anggota)**.

---

## 🗂️ Daftar Isi

1. [Fitur Aplikasi](#fitur-aplikasi)
2. [Struktur Direktori](#struktur-direktori)
3. [Struktur Database](#struktur-database)
4. [Relasi Antar Tabel](#relasi-antar-tabel)
5. [Daftar Lengkap Method](#daftar-lengkap-method)
   - [AuthController](#authcontroller)
   - [Admin — BookController](#admin--bookcontroller)
   - [Admin — CategoryController](#admin--categorycontroller)
   - [Admin — DashboardController](#admin--dashboardcontroller)
   - [Admin — LoanController](#admin--loancontroller)
   - [Admin — MemberController](#admin--membercontroller)
   - [Admin — ProfileController](#admin--profilecontroller)
   - [Admin — ReportController](#admin--reportcontroller)
   - [Admin — UserController](#admin--usercontroller)
   - [User — CatalogController](#user--catalogcontroller)
   - [User — DashboardController](#user--dashboardcontroller)
   - [User — LoanController](#user--loancontroller)
   - [User — NotificationController](#user--notificationcontroller)
   - [User — ProfileController](#user--profilecontroller)
6. [Model & Custom Method](#model--custom-method)
7. [Pemetaan Controller → Model → Tabel](#pemetaan-controller--model--tabel)
8. [Ringkasan Statistik](#ringkasan-statistik)
9. [Instalasi & Konfigurasi](#instalasi--konfigurasi)

---

## Fitur Aplikasi

| Role | Fitur |
|------|-------|
| **Admin** | Kelola buku, kategori, user, anggota, peminjaman (approve/reject/return), laporan PDF & Excel, profil |
| **User** | Katalog buku, ajukan peminjaman, riwayat pinjam, ulasan & rating, notifikasi, profil |
| **Auth** | Login, Register, Lupa Password, Logout |

---

## Struktur Direktori

```
app/
├── Config/
│   ├── Routes.php              # Definisi semua route
│   ├── Filters.php             # Konfigurasi filter auth
│   └── Database.php            # Konfigurasi koneksi DB
│
├── Controllers/
│   ├── AuthController.php      # Login, Register, Logout
│   ├── BaseController.php      # Base class semua controller
│   ├── Home.php                # Halaman utama
│   ├── Admin/
│   │   ├── BookController.php        # CRUD buku
│   │   ├── CategoryController.php    # CRUD kategori
│   │   ├── DashboardController.php   # Dashboard admin
│   │   ├── LoanController.php        # Manajemen peminjaman
│   │   ├── MemberController.php      # Lihat data anggota
│   │   ├── ProfileController.php     # Profil admin
│   │   ├── ReportController.php      # Laporan & export
│   │   └── UserController.php        # CRUD user
│   └── User/
│       ├── CatalogController.php     # Katalog & ulasan buku
│       ├── DashboardController.php   # Dashboard user
│       ├── LoanController.php        # Ajukan & riwayat pinjam
│       ├── NotificationController.php # Notifikasi
│       └── ProfileController.php     # Profil user
│
├── Models/
│   ├── UserModel.php           # Tabel: users
│   ├── AnggotaModel.php        # Tabel: anggota
│   ├── BukuModel.php           # Tabel: buku (+ 2 custom method)
│   ├── KategoriModel.php       # Tabel: kategori
│   ├── PeminjamanModel.php     # Tabel: peminjaman (+ 2 custom method)
│   ├── NotifikasiModel.php     # Tabel: notifikasi
│   └── UlasanModel.php         # Tabel: ulasan (+ 1 custom method)
│
├── Database/
│   ├── Migrations/             # 7 file migrasi tabel
│   ├── Seeds/
│   │   └── MainSeeder.php      # Data awal (user, buku, kategori, dll)
│   └── database.sql            # Full SQL schema + seed data
│
├── Filters/
│   └── AuthFilter.php          # Filter autentikasi & otorisasi
│
└── Views/
    ├── auth/                   # Login, Register, Forgot Password
    ├── admin/                  # View admin (books, loans, users, dll)
    ├── user/                   # View user (catalog, loans, profile, dll)
    └── layouts/                # Layout utama admin & user
```

---

## Struktur Database

Database: `perpus_digital` (MySQL / MariaDB, charset `utf8mb4`)

### Tabel `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id_user` | INT (PK, AI) | Primary key |
| `username` | VARCHAR(50) UNIQUE | Username login |
| `email` | VARCHAR(100) UNIQUE | Email unik |
| `nama_lengkap` | VARCHAR(100) | Nama lengkap |
| `password` | VARCHAR(255) | Password (bcrypt) |
| `foto_profil` | VARCHAR(255) | Path foto, default `default.png` |
| `role` | ENUM('admin','user') | Peran pengguna |
| `status` | TINYINT(1) | 1=aktif, 0=nonaktif |
| `last_login` | DATETIME | Waktu login terakhir |
| `created_at` | DATETIME | Waktu dibuat |
| `updated_at` | DATETIME | Waktu diperbarui |

### Tabel `anggota`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id_anggota` | INT (PK, AI) | Primary key |
| `id_user` | INT UNIQUE (FK → users) | Relasi ke tabel users |
| `nim_nis` | VARCHAR(50) | Nomor induk mahasiswa/siswa |
| `alamat` | TEXT | Alamat anggota |
| `no_telp` | VARCHAR(20) | Nomor telepon |
| `foto_ktp` | VARCHAR(255) | Path foto KTP |
| `created_at` | DATETIME | Waktu dibuat |

> **FK:** `id_user` → `users.id_user` ON DELETE CASCADE

### Tabel `kategori`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id_kategori` | INT (PK, AI) | Primary key |
| `nama_kategori` | VARCHAR(100) | Nama kategori buku |
| `deskripsi` | TEXT | Deskripsi kategori |
| `created_at` | DATETIME | Waktu dibuat |

### Tabel `buku`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id_buku` | INT (PK, AI) | Primary key |
| `id_kategori` | INT (FK → kategori) | Relasi ke kategori |
| `judul_buku` | VARCHAR(200) | Judul buku |
| `pengarang` | VARCHAR(150) | Nama pengarang |
| `penerbit` | VARCHAR(150) | Nama penerbit |
| `tahun_terbit` | YEAR | Tahun penerbitan |
| `isbn` | VARCHAR(20) UNIQUE | Kode ISBN unik |
| `deskripsi` | TEXT | Deskripsi buku |
| `gambar` | VARCHAR(255) | Cover buku, default `no-cover.png` |
| `stok` | INT | Jumlah stok total |
| `dipinjam` | INT | Jumlah yang sedang dipinjam |
| `created_at` | DATETIME | Waktu dibuat |
| `updated_at` | DATETIME | Waktu diperbarui |

> **FK:** `id_kategori` → `kategori.id_kategori` ON DELETE SET NULL

### Tabel `peminjaman`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id_peminjaman` | INT (PK, AI) | Primary key |
| `id_anggota` | INT (FK → anggota) | Anggota peminjam |
| `id_buku` | INT (FK → buku) | Buku yang dipinjam |
| `tanggal_pinjam` | DATE | Tanggal mulai pinjam |
| `tanggal_kembali_rencana` | DATE | Target tanggal kembali |
| `tanggal_kembali_aktual` | DATE | Realisasi tanggal kembali |
| `status` | ENUM('pending','disetujui','ditolak','dikembalikan') | Status peminjaman |
| `alasan_penolakan` | TEXT | Alasan jika ditolak |
| `diproses_oleh` | INT (FK → users) | Admin yang memproses |
| `tanggal_proses` | DATETIME | Waktu diproses |
| `denda` | DECIMAL(10,2) | Denda keterlambatan |
| `created_at` | DATETIME | Waktu dibuat |

> **FK:** `id_anggota` → `anggota.id_anggota`  
> **FK:** `id_buku` → `buku.id_buku`  
> **FK:** `diproses_oleh` → `users.id_user` ON DELETE SET NULL

### Tabel `notifikasi`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id_notifikasi` | INT (PK, AI) | Primary key |
| `id_user` | INT (FK → users) | Penerima notifikasi |
| `judul` | VARCHAR(200) | Judul notifikasi |
| `pesan` | TEXT | Isi pesan |
| `link` | VARCHAR(255) | URL tujuan |
| `is_read` | TINYINT(1) | 0=belum dibaca, 1=sudah dibaca |
| `created_at` | DATETIME | Waktu dibuat |

> **FK:** `id_user` → `users.id_user` ON DELETE CASCADE

### Tabel `ulasan`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id_ulasan` | INT (PK, AI) | Primary key |
| `id_buku` | INT (FK → buku) | Buku yang diulas |
| `id_user` | INT (FK → users) | User yang memberi ulasan |
| `rating` | TINYINT (1–5) | Nilai rating bintang |
| `komentar` | TEXT | Teks komentar |
| `created_at` | DATETIME | Waktu dibuat |

> **UNIQUE KEY:** (`id_buku`, `id_user`) — 1 user hanya boleh 1 ulasan per buku  
> **FK:** `id_buku` → `buku.id_buku` ON DELETE CASCADE  
> **FK:** `id_user` → `users.id_user` ON DELETE CASCADE

---

## Relasi Antar Tabel

```
users ──────────────────────────────────────────────────────────┐
  │ 1                                                            │
  │ (1:1)                                                        │
  ▼ N                                                            │
anggota                                                          │
  │ 1                                                            │
  │ (1:N)                                                        │
  ▼ N                                                            │
peminjaman ◄──────── buku ◄──────── kategori                    │
  │ N (diproses_oleh)  │ 1:N                    │ 1:N           │
  └────────────────────┘                        ▼ N            │
                        ulasan ◄─────────────── users ──────────┘
                                                  │ 1:N
                                                  ▼ N
                                              notifikasi
```

**Ringkasan Relasi:**

| Relasi | Tipe | Keterangan |
|--------|------|------------|
| `users` → `anggota` | 1:1 | Setiap user punya max 1 data anggota |
| `anggota` → `peminjaman` | 1:N | Anggota bisa punya banyak peminjaman |
| `buku` → `peminjaman` | 1:N | Satu buku bisa dipinjam berkali-kali |
| `buku` → `ulasan` | 1:N | Satu buku bisa punya banyak ulasan |
| `users` → `ulasan` | 1:N | User bisa mengulas banyak buku |
| `kategori` → `buku` | 1:N | Kategori memiliki banyak buku |
| `users` → `notifikasi` | 1:N | User bisa punya banyak notifikasi |
| `users` → `peminjaman` (diproses_oleh) | 1:N | Admin bisa memproses banyak peminjaman |

---

## Daftar Lengkap Method

> Total keseluruhan: **56 method** (51 controller + 5 model)

---

### AuthController

**File:** `app/Controllers/AuthController.php`  
**Tabel terkait:** `users`, `anggota`

| No | Method | HTTP | Route | Keterangan |
|----|--------|------|-------|------------|
| 1 | `index()` | GET | `/` | Redirect ke login |
| 2 | `login()` | POST | `/login` | Validasi & proses login, set session |
| 3 | `register()` | GET | `/register` | Tampilkan form registrasi |
| 4 | `doRegister()` | POST | `/register` | Validasi & simpan user + anggota baru |
| 5 | `forgotPassword()` | GET | `/forgot-password` | Tampilkan form lupa password |
| 6 | `doForgotPassword()` | POST | `/forgot-password` | Proses reset password via email |
| 7 | `logout()` | GET | `/logout` | Hapus session & redirect ke login |

---

### Admin — BookController

**File:** `app/Controllers/Admin/BookController.php`  
**Model:** `BukuModel`, `KategoriModel`  
**Tabel:** `buku`, `kategori`

| No | Method | HTTP | Route | Keterangan |
|----|--------|------|-------|------------|
| 1 | `__construct()` | — | — | Load BukuModel & KategoriModel |
| 2 | `index()` | GET | `/admin/books` | Tampil daftar semua buku + join kategori |
| 3 | `create()` | GET | `/admin/books/create` | Form tambah buku baru |
| 4 | `store()` | POST | `/admin/books/store` | Validasi, upload gambar, simpan buku |
| 5 | `edit(int $id)` | GET | `/admin/books/edit/{id}` | Form edit buku berdasarkan ID |
| 6 | `update(int $id)` | POST | `/admin/books/update/{id}` | Validasi, update buku, ganti gambar jika ada |
| 7 | `delete(int $id)` | GET | `/admin/books/delete/{id}` | Hapus buku + file gambar dari server |

---

### Admin — CategoryController

**File:** `app/Controllers/Admin/CategoryController.php`  
**Model:** `KategoriModel`  
**Tabel:** `kategori`

| No | Method | HTTP | Route | Keterangan |
|----|--------|------|-------|------------|
| 1 | `index()` | GET | `/admin/categories` | Tampil daftar kategori |
| 2 | `store()` | POST | `/admin/categories/store` | Tambah kategori baru |
| 3 | `update(int $id)` | POST | `/admin/categories/update/{id}` | Edit nama & deskripsi kategori |
| 4 | `delete(int $id)` | GET | `/admin/categories/delete/{id}` | Hapus kategori |

---

### Admin — DashboardController

**File:** `app/Controllers/Admin/DashboardController.php`  
**Model:** `UserModel`, `BukuModel`, `PeminjamanModel`  
**Tabel:** `users`, `buku`, `peminjaman`

| No | Method | HTTP | Route | Keterangan |
|----|--------|------|-------|------------|
| 1 | `index()` | GET | `/admin/dashboard` | Tampil statistik: total buku, anggota, peminjaman aktif, pending |

---

### Admin — LoanController

**File:** `app/Controllers/Admin/LoanController.php`  
**Model:** `PeminjamanModel`, `BukuModel`, `NotifikasiModel`  
**Tabel:** `peminjaman`, `buku`, `notifikasi`

| No | Method | HTTP | Route | Keterangan |
|----|--------|------|-------|------------|
| 1 | `index()` | GET | `/admin/loans` | Daftar semua peminjaman + filter status |
| 2 | `detail(int $id)` | GET | `/admin/loans/detail/{id}` | Detail satu peminjaman (anggota, buku, status) |
| 3 | `approve(int $id)` | POST | `/admin/loans/approve/{id}` | Setujui pinjam, kurangi stok buku, kirim notifikasi |
| 4 | `reject(int $id)` | POST | `/admin/loans/reject/{id}` | Tolak pinjam + isi alasan, kirim notifikasi |
| 5 | `returnBook(int $id)` | POST | `/admin/loans/return/{id}` | Proses pengembalian, hitung denda, kembalikan stok |
| 6 | `delete(int $id)` | GET | `/admin/loans/delete/{id}` | Hapus data peminjaman |

---

### Admin — MemberController

**File:** `app/Controllers/Admin/MemberController.php`  
**Model:** `AnggotaModel`, `UserModel`  
**Tabel:** `anggota`, `users`

| No | Method | HTTP | Route | Keterangan |
|----|--------|------|-------|------------|
| 1 | `index()` | GET | `/admin/members` | Daftar semua anggota + data user |
| 2 | `detail(int $id)` | GET | `/admin/members/detail/{id}` | Detail profil + riwayat pinjam anggota |

---

### Admin — ProfileController

**File:** `app/Controllers/Admin/ProfileController.php`  
**Model:** `UserModel`  
**Tabel:** `users`

| No | Method | HTTP | Route | Keterangan |
|----|--------|------|-------|------------|
| 1 | `index()` | GET | `/admin/profile` | Tampil profil admin yang login |
| 2 | `update()` | POST | `/admin/profile/update` | Update nama, password, foto profil admin |

---

### Admin — ReportController

**File:** `app/Controllers/Admin/ReportController.php`  
**Model:** `PeminjamanModel`  
**Tabel:** `peminjaman`

| No | Method | HTTP | Route | Keterangan |
|----|--------|------|-------|------------|
| 1 | `index()` | GET | `/admin/reports` | Tampil halaman laporan dengan filter tanggal |
| 2 | `exportPdf()` | GET | `/admin/reports/pdf` | Export laporan peminjaman ke file PDF |
| 3 | `exportExcel()` | GET | `/admin/reports/excel` | Export laporan peminjaman ke file Excel |

---

### Admin — UserController

**File:** `app/Controllers/Admin/UserController.php`  
**Model:** `UserModel`, `AnggotaModel`  
**Tabel:** `users`, `anggota`

| No | Method | HTTP | Route | Keterangan |
|----|--------|------|-------|------------|
| 1 | `index()` | GET | `/admin/users` | Daftar semua user + filter role/status |
| 2 | `create()` | GET | `/admin/users/create` | Form tambah user baru |
| 3 | `store()` | POST | `/admin/users/store` | Validasi & simpan user baru (+ data anggota jika role user) |
| 4 | `edit(int $id)` | GET | `/admin/users/edit/{id}` | Form edit data user |
| 5 | `update(int $id)` | POST | `/admin/users/update/{id}` | Update data user + foto profil |
| 6 | `toggle(int $id)` | GET | `/admin/users/toggle/{id}` | Toggle status aktif/nonaktif user |
| 7 | `delete(int $id)` | GET | `/admin/users/delete/{id}` | Hapus user (cascade ke anggota, notifikasi, ulasan) |

---

### User — CatalogController

**File:** `app/Controllers/User/CatalogController.php`  
**Model:** `BukuModel`, `UlasanModel`  
**Tabel:** `buku`, `kategori`, `ulasan`

| No | Method | HTTP | Route | Keterangan |
|----|--------|------|-------|------------|
| 1 | `index()` | GET | `/user/catalog` | Katalog buku dengan fitur search & filter kategori |
| 2 | `detail(int $id)` | GET | `/user/catalog/detail/{id}` | Detail buku + rata-rata rating + daftar ulasan |
| 3 | `submitReview(int $idBuku)` | POST | `/user/catalog/review/{id}` | Simpan rating & komentar (1 user = 1 ulasan per buku) |

---

### User — DashboardController

**File:** `app/Controllers/User/DashboardController.php`  
**Model:** `PeminjamanModel`  
**Tabel:** `peminjaman`

| No | Method | HTTP | Route | Keterangan |
|----|--------|------|-------|------------|
| 1 | `index()` | GET | `/user/dashboard` | Dashboard user: statistik pinjaman aktif, riwayat, denda |

---

### User — LoanController

**File:** `app/Controllers/User/LoanController.php`  
**Model:** `PeminjamanModel`, `BukuModel`, `AnggotaModel`  
**Tabel:** `peminjaman`, `buku`, `anggota`

| No | Method | HTTP | Route | Keterangan |
|----|--------|------|-------|------------|
| 1 | `index()` | GET | `/user/loans` | Riwayat semua peminjaman milik user yang login |
| 2 | `create(int $idBuku)` | GET | `/user/loans/create/{id}` | Form pengajuan pinjam buku tertentu |
| 3 | `store()` | POST | `/user/loans/store` | Simpan permohonan peminjaman (status: pending) |

---

### User — NotificationController

**File:** `app/Controllers/User/NotificationController.php`  
**Model:** `NotifikasiModel`  
**Tabel:** `notifikasi`

| No | Method | HTTP | Route | Keterangan |
|----|--------|------|-------|------------|
| 1 | `index()` | GET | `/user/notifications` | Daftar semua notifikasi milik user |
| 2 | `markRead(int $id)` | POST | `/user/notifications/read/{id}` | Tandai satu notifikasi sebagai sudah dibaca |
| 3 | `markAllRead()` | POST | `/user/notifications/read-all` | Tandai semua notifikasi sebagai sudah dibaca |

---

### User — ProfileController

**File:** `app/Controllers/User/ProfileController.php`  
**Model:** `UserModel`, `AnggotaModel`  
**Tabel:** `users`, `anggota`

| No | Method | HTTP | Route | Keterangan |
|----|--------|------|-------|------------|
| 1 | `index()` | GET | `/user/profile` | Tampil profil lengkap user (data user + anggota) |
| 2 | `update()` | POST | `/user/profile/update` | Update nama, password, foto profil, NIM, alamat, no_telp |

---

## Model & Custom Method

Setiap model mewarisi `CodeIgniter\Model` dan otomatis memiliki method CRUD bawaan (`find`, `findAll`, `save`, `insert`, `update`, `delete`, dll). Di bawah ini adalah **custom method tambahan** yang didefinisikan secara eksplisit:

### `BukuModel` — `app/Models/BukuModel.php` → Tabel: `buku`

#### `getWithKategori(): array`
Mengambil semua buku beserta nama kategori, rata-rata rating, total ulasan, dan total peminjaman menggunakan LEFT JOIN ke tabel `kategori`, `ulasan`, dan `peminjaman`.

```php
public function getWithKategori(): array
{
    return $this->db->table('buku b')
        ->select('b.*, k.nama_kategori,
                  COALESCE(AVG(u.rating),0) as avg_rating,
                  COUNT(DISTINCT u.id_ulasan) as total_ulasan,
                  COUNT(DISTINCT p.id_peminjaman) as total_pinjam')
        ->join('kategori k','k.id_kategori = b.id_kategori','left')
        ->join('ulasan u','u.id_buku = b.id_buku','left')
        ->join('peminjaman p','p.id_buku = b.id_buku','left')
        ->groupBy('b.id_buku')
        ->get()->getResultArray();
}
```
> **Dipakai di:** `Admin/BookController::index()`, `User/CatalogController::index()`

#### `getDetailWithRelations(int $id): ?array`
Mengambil detail satu buku berdasarkan ID beserta relasi lengkap (kategori, rating, ulasan, peminjaman).

```php
public function getDetailWithRelations(int $id): ?array
{
    return $this->db->table('buku b')
        ->select('b.*, k.nama_kategori, ...')
        ->join(...)->where('b.id_buku', $id)
        ->groupBy('b.id_buku')
        ->get()->getRowArray();
}
```
> **Dipakai di:** `User/CatalogController::detail()`, `User/LoanController::create()`

---

### `PeminjamanModel` — `app/Models/PeminjamanModel.php` → Tabel: `peminjaman`

#### `getWithRelations(array $where = []): array`
Mengambil daftar peminjaman lengkap dengan data anggota (NIM, nama, foto), buku (judul, gambar, pengarang), dan nama admin pemroses. Filter opsional via parameter `$where`.

```php
public function getWithRelations(array $where = []): array
{
    $builder = $this->db->table('peminjaman p')
        ->select('p.*, a.nim_nis, u.nama_lengkap as nama_anggota, ...')
        ->join('anggota a','a.id_anggota = p.id_anggota','left')
        ->join('users u','u.id_user = a.id_user','left')
        ->join('buku b','b.id_buku = p.id_buku','left')
        ->join('users pu','pu.id_user = p.diproses_oleh','left')
        ->orderBy('p.created_at','DESC');
    foreach ($where as $k => $v) $builder->where($k, $v);
    return $builder->get()->getResultArray();
}
```
> **Dipakai di:** `Admin/LoanController::index()`, `User/LoanController::index()`

#### `getDetail(int $id): ?array`
Mengambil detail lengkap satu transaksi peminjaman termasuk data kontak anggota dan info buku.

```php
public function getDetail(int $id): ?array
{
    return $this->db->table('peminjaman p')
        ->select('p.*, a.nim_nis, a.no_telp, a.alamat, ...')
        ->join(...)->where('p.id_peminjaman', $id)
        ->get()->getRowArray();
}
```
> **Dipakai di:** `Admin/LoanController::detail()`, `Admin/LoanController::approve()`, `Admin/LoanController::returnBook()`

---

### `UlasanModel` — `app/Models/UlasanModel.php` → Tabel: `ulasan`

#### `getByBuku(int $idBuku): array`
Mengambil semua ulasan untuk satu buku beserta nama lengkap dan foto profil reviewer, diurutkan terbaru di atas.

```php
public function getByBuku(int $idBuku): array
{
    return $this->db->table('ulasan ul')
        ->select('ul.*, u.nama_lengkap, u.foto_profil')
        ->join('users u','u.id_user = ul.id_user','left')
        ->where('ul.id_buku', $idBuku)
        ->orderBy('ul.created_at','DESC')
        ->get()->getResultArray();
}
```
> **Dipakai di:** `User/CatalogController::detail()`

---

## Pemetaan Controller → Model → Tabel

| Controller | Model yang Digunakan | Tabel Database |
|---|---|---|
| `AuthController` | `UserModel`, `AnggotaModel` | `users`, `anggota` |
| `Admin/BookController` | `BukuModel`, `KategoriModel` | `buku`, `kategori` |
| `Admin/CategoryController` | `KategoriModel` | `kategori` |
| `Admin/DashboardController` | `UserModel`, `BukuModel`, `PeminjamanModel` | `users`, `buku`, `peminjaman` |
| `Admin/LoanController` | `PeminjamanModel`, `BukuModel`, `NotifikasiModel` | `peminjaman`, `buku`, `notifikasi` |
| `Admin/MemberController` | `AnggotaModel`, `UserModel` | `anggota`, `users` |
| `Admin/ProfileController` | `UserModel` | `users` |
| `Admin/ReportController` | `PeminjamanModel` | `peminjaman` |
| `Admin/UserController` | `UserModel`, `AnggotaModel` | `users`, `anggota` |
| `User/CatalogController` | `BukuModel`, `UlasanModel` | `buku`, `ulasan`, `kategori` |
| `User/DashboardController` | `PeminjamanModel` | `peminjaman` |
| `User/LoanController` | `PeminjamanModel`, `BukuModel`, `AnggotaModel` | `peminjaman`, `buku`, `anggota` |
| `User/NotificationController` | `NotifikasiModel` | `notifikasi` |
| `User/ProfileController` | `UserModel`, `AnggotaModel` | `users`, `anggota` |

---

## Ringkasan Statistik

| Kategori | Detail | Jumlah |
|---|---|---|
| **Controller Admin** | 8 file | 32 method |
| **Controller User** | 5 file | 12 method |
| **AuthController** | 1 file | 7 method |
| **Total Method Controller** | 14 file | **51 method** |
| **Custom Method Model** | 3 model | **5 method** |
| **TOTAL KESELURUHAN** | | **56 method** |
| **Tabel Database** | | **7 tabel** |
| **Foreign Key** | | **7 relasi FK** |
| **Migration Files** | | **7 file** |

---

## Instalasi & Konfigurasi

### Prasyarat
- PHP >= 8.1
- MySQL / MariaDB
- Composer
- Web Server (Apache/Nginx)

### Langkah Instalasi

```bash
# 1. Clone / ekstrak project
unzip cifinal.zip -d perpustakaan
cd perpustakaan

# 2. Install dependency
composer install

# 3. Salin file environment
cp env .env

# 4. Edit konfigurasi .env
nano .env
```

```env
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost/perpustakaan/public/'

database.default.hostname = localhost
database.default.database = perpus_digital
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```

```bash
# 5. Buat database
mysql -u root -p -e "CREATE DATABASE perpus_digital CHARACTER SET utf8mb4;"

# 6. Opsi A: Import SQL langsung
mysql -u root -p perpus_digital < app/Database/database.sql

# 7. Opsi B: Jalankan migrasi + seeder
php spark migrate
php spark db:seed MainSeeder

# 8. Jalankan server
php spark serve
```

### Akun Default

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin123` |
| User | `budi` | `admin123` |
| User | `sari` | `admin123` |

### Struktur Upload

Pastikan folder berikut dapat ditulis (writable):
```
writable/
public/uploads/
public/uploads/books/
public/uploads/profiles/
public/uploads/ktp/
```

---

*Dokumentasi dibuat otomatis — Aplikasi Perpustakaan Digital v1.0 · CodeIgniter 4*
