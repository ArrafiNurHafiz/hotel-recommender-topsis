<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP"/>
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"/>
  <img src="https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap"/>
  <img src="https://img.shields.io/badge/MVC-FF6F00?style=for-the-badge&logo=codeigniter&logoColor=white" alt="MVC"/>
  <img src="https://img.shields.io/badge/license-MIT-green?style=for-the-badge" alt="MIT"/>
</p>

<p align="center">
  <img src="https://capsule-render.vercel.app/api?type=waving&height=200&color=0:1a237e,100:0277bd&text=Hotel%20Recommender&fontColor=ffffff&fontAlign=50&fontAlignY=35&desc=Entropy-TOPSIS%20%7C%20MCDM%20%7C%20PHP%20MVC&descAlign=50&descAlignY=55"/>
</p>

<p align="center">
  Sistem Rekomendasi Hotel berbasis <b>Multi-Criteria Decision Making (MCDM)</b> yang menggabungkan<br>
  <b>Shannon Entropy</b> untuk pembobotan dinamis dan <b>TOPSIS</b> untuk pemeringkatan rekomendasi hotel.
</p>

---

## ✨ Highlights

| | | |
|---|---|---|
| 🎯 **Entropy-TOPSIS** | Bobot kriteria dihitung otomatis dari data — tanpa campur tangan manual | |
| 📍 **Haversine Distance** | Jarak dihitung real-time dari koordinat GPS pengguna | |
| 🏛️ **PHP MVC Pure** | Zero framework — arsitektur MVC murni, lightweight & cepat | |
| 🧩 **3 Role System** | User → Admin Hotel → Super Admin, masing-masing dengan akses terpisah | |
| 🗄️ **147 Hotel &#124; 441 Kamar** | Data realistik siap pakai dari seeder | |

---

## 🧠 Algoritma

```
User Location → Hitung Jarak (Haversine) → Matriks Keputusan
    → Shannon Entropy (bobot otomatis) → Matriks Terbobot
    → TOPSIS (A+ / A-) → Ranking → Rekomendasi
```

| Kriteria | Tipe | Sumber |
|---|---|---|
| 💰 Harga Terendah | Cost | Harga kamar termurah |
| ⭐ Rating Rata-rata | Benefit | Review pengguna |
| 🏷️ Jumlah Fasilitas | Benefit | Fasilitas hotel |
| 📏 Jarak Geografis | Cost | Haversine formula |
| 🛌 Tingkat Okupansi | Cost | Keterisian kamar |

---

## 🚀 Instalasi

### Prasyarat

- PHP 7.4+ (ekstensi: `pdo_mysql`)
- MySQL / MariaDB
- Apache (`mod_rewrite`) atau PHP built-in server

### 1. Clone

```bash
git clone https://github.com/ArrafiNurHafiz/hotel-recommender-topsis.git
cd hotel-recommender-topsis
```

### 2. Konfigurasi Database

```php
// config/database.php
'password' => getenv('DB_PASS') ?: '' // isi password MySQL kamu
```

Atau pakai environment variable: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.

### 3. Setup & Seed (1 Perintah)

```bash
php database/reset.php
```

> Drop database lama → buat ulang → migrasi tabel → seed 20 hotel + 441 kamar.

### 4. Jalankan

```bash
php -S localhost:8080
```

Buka **http://localhost:8080** 🎉

---

## 🔑 Akun Login

### 👑 Super Admin
```
Email:       superadmin@hotel.com
Password:    superadmin123
```

### 🏪 Admin Hotel
| Email | Password | Mengelola |
|---|---|---|
| `admin.a@hotel.com` | `admina123` | Grand Hyatt, Mulia Senayan, Shangri-La & 7 hotel lain |
| `admin.b@hotel.com` | `adminb123` | Ritz-Carlton, Pullman, Padma Bandung & 7 hotel lain |

### 👤 User Biasa
```
Email:       user@hotel.com
Password:    user123
```

---

## 🗺️ Rute Penting

| URL | Akses | Fungsi |
|---|---|---|
| `/` | Public | Beranda |
| `/hotels` | Public | Daftar hotel |
| `/hotels/{id}` | Public | Detail hotel |
| `/recommendations?city=Jakarta` | Public | Rekomendasi Entropy-TOPSIS |
| `/login` | Guest | Login user |
| `/register` | Guest | Registrasi user |
| `/my-bookings` | User | Riwayat booking |
| `/booking/{id}` | User | Form booking kamar |
| `/admin/login` | Guest | Login admin hotel |
| `/admin/dashboard` | Admin Hotel | Dashboard |
| `/admin/rooms` | Admin Hotel | Kelola kamar |
| `/admin/bookings` | Admin Hotel | Kelola booking |
| `/admin/reviews` | Admin Hotel | Lihat review |
| `/super-admin/login` | Guest | Login super admin |
| `/super-admin/dashboard` | Super Admin | Dashboard |
| `/super-admin/users` | Super Admin | Kelola user |
| `/super-admin/hotels` | Super Admin | Verifikasi hotel |
| `/super-admin/monitoring` | Super Admin | Monitoring global |

---

## 📁 Struktur Proyek

```
├── app/
│   ├── controllers/          # Logika bisnis
│   ├── models/               # Query database per entitas
│   ├── services/
│   │   └── EntropyTopsis.php # ⚡ Algoritma inti
│   └── views/                # Template Bootstrap 5
├── config/
│   ├── app.php               # Konfigurasi aplikasi
│   └── database.php          # Koneksi MySQL via PDO
├── core/                     # Router, Database, Middleware
├── database/
│   ├── migration.sql         # Skema database
│   ├── seed.php              # Data contoh (20 hotel)
│   └── reset.php             # Reset + seed otomatis
├── public/                   # CSS, JS, gambar
├── routes.php                # Route definitions
└── index.php                 # Entry point
```

---

## ⚙️ Tech Stack

| Lapisan | Teknologi |
|---|---|
| **Backend** | PHP 8.4, Custom MVC, PDO |
| **Database** | MySQL 8.4 |
| **Frontend** | Bootstrap 5.3.3, Google Fonts |
| **Algoritma** | Shannon Entropy + TOPSIS + Haversine |
| **Auth** | BCrypt, Session-based |

---

## 📸 Screenshots

| Halaman | Preview |
|---|---|
| **Beranda** | menampilkan hero & info sistem |
| **Rekomendasi** | daftar hotel terurut skor TOPSIS |
| **Dashboard Admin** | statistik & manajemen hotel |
| **Super Admin** | monitoring global & verifikasi |

> *Screenshots akan ditambahkan setelah deployment.*

---

<p align="center">
  <sub>Built with for Final Project</sub>
</p>

<p align="center">
  <a href="https://github.com/ArrafiNurHafiz/hotel-recommender-topsis">
    <img src="https://img.shields.io/github/stars/ArrafiNurHafiz/hotel-recommender-topsis?style=social"/>
  </a>
</p>
