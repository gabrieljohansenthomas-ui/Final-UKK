# 📚 Perpustakaan Digital — CodeIgniter 4

Aplikasi manajemen perpustakaan berbasis web lengkap dengan dua peran (Admin & User), dibangun menggunakan **CodeIgniter 4 + MySQL + Tailwind CSS + Alpine.js**.

---

## 🚀 Quick Start (5 Langkah)

### Prasyarat
- PHP ≥ 7.4 (rekomendasi PHP 8.1)
- MySQL 5.7+ / MariaDB
- Composer
- Web server (Apache/Nginx) **atau** PHP built-in server

---

### Langkah 1 — Install CodeIgniter 4

```bash
composer create-project codeigniter4/appstarter perpus_digital
cd perpus_digital
```

### Langkah 2 — Copy file proyek

Salin seluruh isi folder `perpus_digital` (dari paket ini) ke dalam folder CI4 yang baru dibuat. Timpa file yang ada.

```
perpus_digital/
├── app/
│   ├── Config/
│   │   ├── Routes.php          ← timpa
│   │   └── Filters.php         ← timpa
│   ├── Controllers/
│   │   ├── BaseController.php  ← timpa
│   │   ├── AuthController.php  ← baru
│   │   ├── Admin/              ← folder baru
│   │   └── User/               ← folder baru
│   ├── Models/
│   │   ├── UserModel.php       ← baru
│   │   ├── AnggotaModel.php    ← baru
│   │   └── Models.php          ← baru (split jika perlu)
│   ├── Views/
│   │   ├── layouts/            ← folder baru
│   │   ├── auth/               ← folder baru
│   │   ├── admin/              ← folder baru
│   │   └── user/               ← folder baru
│   └── Filters/
│       └── AuthFilter.php      ← baru
├── public/
│   └── uploads/
│       ├── covers/             ← buat folder ini
│       └── profiles/           ← buat folder ini
└── .env                        ← timpa & konfigurasi
```

### Langkah 3 — Konfigurasi Database

Edit `.env`:
```env
database.default.hostname = localhost
database.default.database = perpus_digital
database.default.username = root
database.default.password = YOUR_PASSWORD
```

### Langkah 4 — Import Database

```bash
mysql -u root -p -e "SOURCE database.sql"
# atau buka phpMyAdmin dan import file database.sql
```

### Langkah 5 — Buat Folder Upload & Jalankan

```bash
mkdir -p public/uploads/covers public/uploads/profiles

# Salin gambar placeholder (opsional):
# public/uploads/covers/no-cover.png
# public/uploads/profiles/default.png

php spark serve
```

Buka browser: **http://localhost:8080**

---

## 🔑 Akun Demo

| Role  | Username | Password  |
|-------|----------|-----------|
| Admin | `admin`  | `password` |
| User  | `budi`   | `password` |
| User  | `sari`   | `password` |
| User  | `andi`   | `password` |

> **Catatan:** Password di database di-hash dengan `password_hash()`. Gunakan `password_verify()` untuk verifikasi. Seed data menggunakan hash dari `password`.

---

## 🏗️ Arsitektur Teknologi

```
┌─────────────────────────────────────────┐
│           FRONTEND LAYER                │
│  Tailwind CSS (CDN) + Alpine.js (CDN)   │
│  Chart.js · Font Awesome · Google Fonts │
└──────────────────┬──────────────────────┘
                   │ HTTP Request
┌──────────────────▼──────────────────────┐
│           CODEIGNITER 4 (MVC)           │
│                                         │
│  Routes.php → AuthFilter → Controllers │
│       ↓              ↓                  │
│   Models (Query Builder)    Views       │
│       ↓                                 │
└──────────────────┬──────────────────────┘
                   │ SQL
┌──────────────────▼──────────────────────┐
│         MySQL DATABASE                  │
│  users · anggota · kategori · buku      │
│  peminjaman · notifikasi · ulasan       │
└─────────────────────────────────────────┘
```

---

## 📊 ERD (Entity Relationship Diagram)

```
users ─────────── anggota (1:1)
  │                   │
  │               peminjaman ────── buku ────── kategori
  │                                   │
  ├─── notifikasi                  ulasan
  └─── ulasan
  └─── peminjaman (sebagai pemroses)
```

---

## 🔄 Alur Kerja

### Alur Admin
```
Login → Dashboard → 
  ├── Manajemen Buku (CRUD + upload cover)
  ├── Manajemen Kategori (CRUD)
  ├── Manajemen User (CRUD + toggle aktif)
  ├── Manajemen Anggota (view + detail)
  ├── Manajemen Peminjaman
  │     ├── Terima → kurangi stok → notifikasi user
  │     ├── Tolak  → alasan wajib → notifikasi user
  │     └── Kembalikan → hitung denda → tambah stok → notifikasi
  ├── Laporan (ringkasan, populer, aktif, export*)
  └── Profil (edit + upload foto)
```

### Alur User
```
Register → Login → Dashboard →
  ├── Katalog Buku
  │     ├── Filter kategori + pencarian
  │     ├── Detail buku → lihat ulasan
  │     ├── Beri/update ulasan (rating + komentar)
  │     └── Klik Pinjam → Form Peminjaman → Submit (pending)
  ├── Riwayat Peminjaman (lihat status, denda, alasan tolak)
  ├── Notifikasi (klik → mark read → redirect)
  └── Profil (edit data diri + foto)
```

---

## 🧱 Struktur Kode Lengkap

```
app/
├── Config/
│   ├── Routes.php              — Semua route (public/admin/user)
│   └── Filters.php             — Daftarkan AuthFilter
│
├── Controllers/
│   ├── BaseController.php      — Session, DB, helper views, sendNotification()
│   ├── AuthController.php      — Login, Register, ForgotPw, Logout
│   ├── Admin/
│   │   ├── DashboardController.php  — Statistik + chart data
│   │   ├── BookController.php       — CRUD buku + file upload
│   │   ├── CategoryController.php   — CRUD kategori
│   │   ├── UserController.php       — CRUD user + toggle status
│   │   ├── MemberController.php     — Daftar + detail anggota
│   │   ├── LoanController.php       — Proses peminjaman
│   │   ├── ReportController.php     — Laporan + export placeholder
│   │   └── ProfileController.php   — Edit profil admin
│   └── User/
│       ├── DashboardController.php  — Stat + rekomendasi
│       ├── CatalogController.php    — Katalog + detail + review
│       ├── LoanController.php       — Riwayat + form pinjam
│       ├── NotificationController.php — Notif + mark read
│       └── ProfileController.php   — Edit profil user
│
├── Models/
│   ├── UserModel.php           — CI4 Model users
│   ├── AnggotaModel.php        — CI4 Model anggota
│   └── Models.php              — BukuModel, KategoriModel,
│                                  PeminjamanModel, NotifikasiModel, UlasanModel
│
├── Filters/
│   └── AuthFilter.php          — Cek session + role
│
└── Views/
    ├── layouts/
    │   ├── admin_layout.php    — Sidebar indigo + dark mode
    │   └── user_layout.php     — Sidebar emerald + dark mode
    ├── auth/
    │   ├── login.php
    │   ├── register.php
    │   └── forgot_password.php
    ├── admin/
    │   ├── dashboard.php       — Chart.js + statistik
    │   ├── books/{index,form}.php
    │   ├── categories/index.php
    │   ├── loans/{index,detail}.php
    │   ├── members/{index,detail}.php
    │   ├── users/{index,form}.php
    │   ├── reports/index.php
    │   └── profile/index.php
    └── user/
        ├── dashboard.php
        ├── catalog/{index,detail}.php
        ├── loans/{index,create}.php
        ├── notifications/index.php
        └── profile/index.php
```

---

## ✨ Fitur Utama per Modul

| Modul | Fitur |
|-------|-------|
| **Auth** | Login email/username, Register + auto buat anggota, CSRF, password hash, session role |
| **Dashboard Admin** | 5 stat cards, grafik Chart.js 12 bulan, buku populer, 5 pinjam terbaru |
| **Buku** | CRUD, upload cover (drag&drop + preview), filter + pencarian, badge stok tersedia |
| **Kategori** | CRUD inline modal, proteksi hapus jika digunakan |
| **Peminjaman** | Terima/tolak (alasan wajib)/kembalikan, hitung denda Rp1000/hari, notifikasi otomatis |
| **Anggota** | Daftar + detail lengkap + riwayat pinjam |
| **Manajemen User** | CRUD + filter/sort/search, toggle aktif/nonaktif, hapus (protect diri sendiri) |
| **Laporan** | Ringkasan denda + total pinjam, 10 buku populer, 10 anggota aktif |
| **Katalog User** | Grid buku, filter kategori, rating bintang, badge ketersediaan |
| **Detail Buku** | Info lengkap, rating avg, form ulasan (1 per user, bisa update), daftar ulasan |
| **Notifikasi** | Badge counter, mark read, mark all read, redirect ke link |
| **Profil** | Edit data + foto profil (preview live + drag&drop), update session |
| **Dark Mode** | Toggle via sidebar, simpan localStorage, semua komponen support |

---

## ❓ Persiapan Presentasi / Pertanyaan Asesor

### Q: Mengapa memilih CodeIgniter 4?
**A:** CI4 adalah framework PHP ringan dengan arsitektur MVC yang rapi. Keunggulannya: learning curve rendah, Query Builder yang aman dari SQL injection, sistem filter/middleware bawaan, validasi terintegrasi, dan cocok untuk PHP 7.4+. Berbeda dengan Laravel yang lebih "magic", CI4 sangat transparan sehingga mudah dipahami dan di-debug.

### Q: Bagaimana keamanan aplikasi ini?
**A:** Beberapa lapisan keamanan diterapkan:
1. **CSRF Token** — setiap form dilindungi `csrf_field()`
2. **Password Hashing** — `password_hash()` dengan algoritma bcrypt
3. **AuthFilter** — memblokir akses tidak sah via session + role check
4. **SQL Injection Prevention** — menggunakan CI4 Query Builder (parameterized)
5. **File Upload Validation** — validasi mime type + ukuran file
6. **XSS Prevention** — menggunakan `esc()` di semua output view

### Q: Bagaimana cara kerja perhitungan denda?
**A:** Ketika admin menekan "Kembalikan", sistem membandingkan `tanggal_kembali_aktual` (hari ini) dengan `tanggal_kembali_rencana`. Jika terlambat, denda = `ceil(jumlah_hari_terlambat) × Rp1.000`. Hasilnya disimpan di kolom `denda` tabel `peminjaman`.

### Q: Apa itu Alpine.js dan mengapa digunakan?
**A:** Alpine.js adalah framework JavaScript ringan (~15kb) untuk interaktivitas UI reaktif langsung di HTML. Digunakan untuk: toggle dark mode, preview gambar, modal konfirmasi hapus, form ulasan bintang interaktif, dan flash message auto-hide. Lebih ringan dari Vue/React untuk kebutuhan ini.

### Q: Bagaimana sistem notifikasi bekerja?
**A:** Notifikasi disimpan di tabel `notifikasi` (id_user, judul, pesan, link, is_read). Setiap action peminjaman (setujui/tolak/kembalikan) memanggil `sendNotification()` di BaseController yang meng-insert record baru. Badge counter di header dihitung real-time via query. Klik notifikasi → mark is_read=1 → redirect ke link.

### Q: Apakah ada batasan peminjaman per user?
**A:** Saat ini sistem mencegah user meminjam buku yang sama jika masih ada peminjaman `pending` atau `disetujui` untuk buku tersebut. Batasan jumlah buku berbeda bisa ditambahkan di `LoanController::create()`.

### Q: Bagaimana dark mode diimplementasikan?
**A:** Menggunakan kelas `dark:` Tailwind CSS dengan strategi `class`. Toggle via Alpine.js yang mengubah atribut class di `<html>`. Preferensi disimpan di `localStorage` agar persisten antar sesi. Semua komponen (sidebar, card, tabel, form) memiliki variant `dark:` yang sesuai.

---

## 🔧 Tips Konfigurasi Tambahan

### Untuk production:
```env
CI_ENVIRONMENT = production
app.baseURL = 'https://yourdomain.com/'
```

### Jika CSRF bermasalah:
```env
app.CSRFProtection = false  # hanya untuk debug
```

### Upload folder permissions (Linux):
```bash
chmod -R 775 public/uploads/
chmod -R 775 writable/
```

---

## 📝 Catatan Pengembangan Lanjutan

- [ ] Export PDF nyata menggunakan mPDF / TCPDF
- [ ] Export Excel menggunakan PhpSpreadsheet  
- [ ] Pagination untuk daftar buku/peminjaman
- [ ] Email notifikasi via SMTP (CI4 Email library)
- [ ] Reset password via email (token-based)
- [ ] Barcode/QR code untuk kartu anggota
- [ ] API endpoint untuk mobile app
- [ ] Upload foto KTP anggota
- [ ] Sistem reminder otomatis (CLI command + cron)

---

*Dibuat untuk keperluan tugas/proyek perpustakaan digital — CodeIgniter 4*
