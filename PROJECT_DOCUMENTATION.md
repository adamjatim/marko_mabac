# SPK Pemilihan Mobil dengan Metode MABAC

Sistem Pendukung Keputusan (SPK) untuk pemilihan mobil menggunakan metode MABAC (Multi-Attributive Border Approximation Area Comparison) yang dibangun dengan Laravel 12, SQLite, dan Tailwind CSS.

## 🎯 Fitur Utama

### Untuk Guest (Pengunjung Umum)
- ✅ Melihat daftar semua mobil dengan spesifikasi lengkap
- ✅ Melihat detail mobil individual
- ✅ Mengakses halaman kriteria penilaian
- ✅ Melakukan perhitungan MABAC dengan bobot custom
- ✅ Melihat hasil rekomendasi mobil terbaik

### Untuk Admin (dengan Login)
- ✅ Login ke dashboard admin
- ✅ CRUD (Create, Read, Update, Delete) data mobil
- ✅ Mengelola kriteria dan bobot default
- ✅ Dashboard dengan statistik sistem

## 📋 Data Sistem

### Mobil Tersedia
1. Toyota Avanza (2024) - MPV
2. Honda City (2024) - City Car
3. Suzuki Ertiga (2024) - MPV
4. Honda Accord (2024) - Sedan
5. Toyota Corolla (2024) - Sedan
6. Wuling Air EV (2024) - Electric Car
7. Daihatsu Rocky (2024) - Compact SUV
8. Hyundai Creta (2024) - Compact SUV
9. BMW X5 (2024) - Premium SUV
10. Mercedes-Benz C-Class (2024) - Premium SUV

### Kriteria Penilaian (7 Kriteria)
1. **Harga Baru** (Cost) - Semakin rendah semakin baik
2. **Harga Jual Kembali** (Benefit) - Semakin tinggi semakin baik
3. **Fitur Keamanan** (Benefit) - Semakin banyak semakin baik
4. **Fitur Kenyamanan** (Benefit) - Semakin banyak semakin baik
5. **Jarak Tempuh** (Benefit) - Semakin jauh semakin baik (km/liter)
6. **Kapasitas Mesin** (Benefit) - Semakin besar semakin baik (cc)
7. **Pajak Kendaraan** (Cost) - Semakin rendah semakin baik

## 🚀 Cara Menggunakan

### 1. Setup Awal
```bash
# Install dependencies
composer install
npm install

# Run migrations dan seed database
php artisan migrate:fresh --seed

# Build assets
npm run build
```

### 2. Menjalankan Aplikasi
```bash
# Development mode
composer run dev

# Atau secara terpisah:
# Terminal 1:
php artisan serve

# Terminal 2:
php artisan queue:listen

# Terminal 3:
npm run dev
```

Aplikasi akan berjalan di `http://localhost:8000`

### 3. Demo Login Admin
```
Email: test@example.com
Password: password
```

## 📐 Metode MABAC

MABAC adalah metode pengambilan keputusan multi-kriteria dengan langkah-langkah:

1. **Normalisasi Data**: Mengubah data mentah ke skala 1-5
2. **Pembobotan**: Mengalikan nilai dengan bobot kriteria
3. **Boundary Approximation Area (BAA)**: Menentukan area referensi
   - Untuk Benefit: minimum nilai terbobot
   - Untuk Cost: maximum nilai terbobot
4. **Q Matrix**: Menghitung jarak dari BAA
5. **Scoring**: Menjumlahkan Q matrix untuk setiap alternatif
6. **Ranking**: Mengurutkan hasil dari skor tertinggi

## 📁 Struktur Folder

```
app/
├── Http/Controllers/
│   ├── HomeController.php
│   ├── MobilController.php
│   ├── KriteriaController.php
│   ├── PerhitunganController.php (MABAC Logic)
│   ├── Auth/LoginController.php
│   └── Admin/
│       ├── AdminController.php
│       ├── MobilController.php
│       └── KriteriaController.php
├── Models/
│   ├── User.php
│   ├── Mobil.php
│   └── Kriteria.php
database/
├── migrations/
│   ├── create_kriterias_table.php
│   └── create_mobils_table.php
└── seeders/
    ├── KriteriaSeeder.php
    └── MobilSeeder.php
resources/views/
├── layouts/
│   ├── app.blade.php
│   ├── navbar.blade.php
│   └── footer.blade.php
├── home.blade.php
├── mobil/
│   ├── index.blade.php
│   └── show.blade.php
├── kriteria/
│   └── index.blade.php
├── perhitungan/
│   ├── index.blade.php
│   └── hasil.blade.php
├── auth/
│   └── login.blade.php
└── admin/
    ├── dashboard.blade.php
    ├── mobil/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    └── kriteria/
        ├── index.blade.php
        └── edit.blade.php
```

## 🛣️ Routes Tersedia

### Guest Routes
| Method | Route | Controller |
|--------|-------|-----------|
| GET | `/` | HomeController@index |
| GET | `/mobil` | MobilController@index |
| GET | `/mobil/{id}` | MobilController@show |
| GET | `/kriteria` | KriteriaController@index |
| GET | `/perhitungan` | PerhitunganController@index |
| POST | `/perhitungan` | PerhitunganController@calculate |
| GET | `/login` | LoginController (view) |
| POST | `/login` | Auth\LoginController@login |

### Admin Routes (Requires Auth)
| Method | Route | Controller |
|--------|-------|-----------|
| GET | `/admin/dashboard` | Admin\AdminController@dashboard |
| GET | `/admin/mobil` | Admin\MobilController@index |
| GET | `/admin/mobil/create` | Admin\MobilController@create |
| POST | `/admin/mobil` | Admin\MobilController@store |
| GET | `/admin/mobil/{id}/edit` | Admin\MobilController@edit |
| PUT | `/admin/mobil/{id}` | Admin\MobilController@update |
| DELETE | `/admin/mobil/{id}` | Admin\MobilController@destroy |
| GET | `/admin/kriteria` | Admin\KriteriaController@index |
| GET | `/admin/kriteria/{id}/edit` | Admin\KriteriaController@edit |
| PUT | `/admin/kriteria/{id}` | Admin\KriteriaController@update |
| POST | `/logout` | Auth\LoginController@logout |

## 🎨 Teknologi yang Digunakan

- **Backend**: Laravel 12
- **Database**: SQLite
- **Frontend**: Blade Templates + Tailwind CSS 4
- **Build Tool**: Vite
- **Package Manager**: Composer, npm

## 📝 Bahasa

Seluruh antarmuka menggunakan **Bahasa Indonesia** untuk kemudahan pengguna lokal.

## 🔒 Keamanan

- CSRF Protection (token di setiap form)
- Password hashing menggunakan bcrypt
- Middleware authentication untuk admin routes
- Input validation di setiap form
- Database seeding dengan test user

## 📊 Database

### Tabel Mobils
- id (Primary Key)
- merk
- model
- tahun
- tipe
- harga_baru
- harga_jual_kembali
- fitur_keamanan
- fitur_kenyamanan
- jarak_tempuh
- kapasitas_mesin
- pajak
- gambar (nullable)
- timestamps

### Tabel Kriterias
- id (Primary Key)
- nama
- tipe (benefit/cost)
- bobot_default
- timestamps

### Tabel Users
- id (Primary Key)
- name
- email (unique)
- password
- timestamps

## 💡 Contoh Penggunaan MABAC

1. Buka halaman `/perhitungan`
2. Sesuaikan bobot kriteria sesuai preferensi (default sudah tersedia)
3. Klik "Hitung Rekomendasi"
4. Lihat hasil ranking mobil berdasarkan skor MABAC
5. Mobil dengan skor tertinggi adalah rekomendasi terbaik

## 🚧 Pengembangan Lebih Lanjut

Fitur yang dapat ditambahkan:
- Upload foto mobil
- Filter mobil berdasarkan tipe/harga
- Perbandingan 2-3 mobil
- Export hasil analisis (PDF/Excel)
- Analytics dashboard untuk admin
- API endpoints
- Mobile application

## 📞 Support

Untuk pertanyaan atau laporan bug, silakan hubungi developer.

---

**Dibuat dengan ❤️ menggunakan Laravel 12**
