# Hotel Recommender - Entropy-TOPSIS

[![PHP Version](https://img.shields.io/badge/php-%5E7.4%20%7C%20%5E8.0-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

Sistem Rekomendasi Hotel berbasis web yang mengimplementasikan metode **Entropy-TOPSIS** (MCDM) untuk merekomendasikan hotel terbaik berdasarkan posisi geografis pengguna.

---

## Fitur Utama

- **User**: registrasi, login, rekomendasi hotel berbasis GPS, booking kamar, review & rating
- **Admin Hotel**: dashboard, kelola kamar, kelola booking (konfirmasi/checkout/batal), lihat review
- **Super Admin**: kelola user, verifikasi hotel, monitoring global

---

## Persyaratan Sistem

- PHP 7.4+
- MySQL / MariaDB
- Apache (`mod_rewrite`) atau PHP built-in server
- ekstensi PHP: `pdo_mysql`, `bcrypt`

---

## Instalasi

### 1. Clone Repositori

```bash
git clone https://github.com/ArrafiNurHafiz/hotel-recommender-topsis.git
cd hotel-recommender-topsis
```

### 2. Konfigurasi Database

Buka `config/database.php` dan sesuaikan kredensial MySQL:

```php
return [
    'host'     => 'localhost',
    'dbname'   => 'hotel_recommendation',
    'username' => 'root',          // ganti dengan user MySQL Anda
    'password' => '',              // ganti dengan password MySQL Anda
    'charset'  => 'utf8mb4',
];
```

Atau gunakan environment variable: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.

### 3. Setup Database & Seed Data

Jalankan script reset untuk membuat database, tabel, dan data contoh:

```bash
php database/reset.php
```

Atau manual:
1. Import `database/migration.sql` ke MySQL
2. Jalankan `php database/seed.php`

### 4. Jalankan Aplikasi

**Via PHP built-in server:**

```bash
php -S localhost:8080
```

**Via Apache:** pastikan `mod_rewrite` aktif dan `AllowOverride All` di konfigurasi VirtualHost.

Buka browser: `http://localhost:8080`

---

## Akun Pengguna (Seeder)

### Super Admin
| Email | Password |
|---|---|
| `superadmin@hotel.com` | `superadmin123` |

### Admin Hotel
| Email | Password | Kelola Hotel |
|---|---|---|
| `admin.a@hotel.com` | `admina123` | Grand Hyatt, Mulia Senayan, Shangri-La, dll (10 hotel) |
| `admin.b@hotel.com` | `adminb123` | Ritz-Carlton, Pullman, Padma Bandung, dll (10 hotel) |

### User Biasa
| Email | Password |
|---|---|
| `user@hotel.com` | `user123` |

---

## Rute Penting

| URL | Keterangan |
|---|---|
| `/` | Beranda |
| `/hotels` | Daftar hotel |
| `/hotels/{id}` | Detail hotel |
| `/recommendations?city=Jakarta` | Rekomendasi Entropy-TOPSIS |
| `/login` | Login user |
| `/register` | Registrasi user |
| `/my-bookings` | Booking saya |
| `/admin/login` | Login admin hotel |
| `/admin/dashboard` | Dashboard admin hotel |
| `/super-admin/login` | Login super admin |
| `/super-admin/dashboard` | Dashboard super admin |

---

## Struktur Direktori

```
├── app/
│   ├── controllers/       # Controller
│   ├── models/            # Model
│   ├── services/          # EntropyTopsis.php (algoritma)
│   └── views/             # Template HTML
├── config/
│   ├── app.php
│   └── database.php
├── core/                  # Router, Database PDO, Middleware
├── database/
│   ├── migration.sql      # Skema database
│   ├── seed.php           # Seeder
│   └── reset.php          # Reset + seed (satu langkah)
├── public/                # CSS, JS, gambar
├── routes.php
├── .htaccess
└── index.php
```

---

## Lisensi

MIT
