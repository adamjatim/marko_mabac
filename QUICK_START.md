# 🚀 Quick Start Guide - SPK Pemilihan Mobil MABAC

## 1️⃣ Mulai Server

```bash
cd c:/Users/adel/Documents/marko_mabac
composer run dev
```

Server akan berjalan di `http://localhost:8000`

## 2️⃣ Akses Halaman

### Untuk Guest (Pengunjung)
- **Beranda**: http://localhost:8000/
- **Daftar Mobil**: http://localhost:8000/mobil
- **Detail Mobil**: http://localhost:8000/mobil/1
- **Kriteria**: http://localhost:8000/kriteria
- **Perhitungan**: http://localhost:8000/perhitungan

### Untuk Admin
- **Login**: http://localhost:8000/login
  - Email: `test@example.com`
  - Password: `password`
- **Dashboard**: http://localhost:8000/admin/dashboard
- **Kelola Mobil**: http://localhost:8000/admin/mobil
- **Kelola Kriteria**: http://localhost:8000/admin/kriteria

## 3️⃣ Fitur Utama

### Guest Features
✅ Lihat 10 mobil dengan spesifikasi lengkap  
✅ Lihat detail setiap mobil  
✅ Lihat 7 kriteria penilaian  
✅ Lakukan perhitungan MABAC dengan bobot custom  
✅ Lihat ranking mobil hasil analisis  

### Admin Features
✅ Tambah, edit, hapus data mobil  
✅ Update bobot default kriteria  
✅ Lihat statistik di dashboard  
✅ Kelola data dari browser  

## 4️⃣ Cara Menggunakan Perhitungan MABAC

1. Buka http://localhost:8000/perhitungan
2. Sesuaikan bobot kriteria sesuai preferensi Anda (atau gunakan default)
3. Klik tombol "Hitung Rekomendasi"
4. Lihat hasil ranking dengan score MABAC
5. Mobil dengan skor tertinggi (🥇) adalah pilihan terbaik

## 5️⃣ Data Sistem

**10 Mobil:**
1. Toyota Avanza - MPV
2. Honda City - City Car
3. Suzuki Ertiga - MPV
4. Honda Accord - Sedan
5. Toyota Corolla - Sedan
6. Wuling Air EV - Electric Car
7. Daihatsu Rocky - Compact SUV
8. Hyundai Creta - Compact SUV
9. BMW X5 - Premium SUV
10. Mercedes-Benz C-Class - Premium SUV

**7 Kriteria:**
1. Harga Baru (Semakin rendah semakin baik)
2. Harga Jual Kembali (Semakin tinggi semakin baik)
3. Fitur Keamanan (Semakin banyak semakin baik)
4. Fitur Kenyamanan (Semakin banyak semakin baik)
5. Jarak Tempuh (Semakin jauh semakin baik)
6. Kapasitas Mesin (Semakin besar semakin baik)
7. Pajak Kendaraan (Semakin rendah semakin baik)

## 6️⃣ Troubleshooting

**Q: Server tidak berjalan**
```bash
# Pastikan Node.js sudah terinstall
node -v
npm -v

# Jalankan:
npm install
php artisan serve
```

**Q: Halaman blank/error**
```bash
# Clear cache
php artisan config:cache
php artisan cache:clear

# Restart server
```

**Q: Database error**
```bash
# Reset database dan seed ulang
php artisan migrate:fresh --seed
```

**Q: Tidak bisa login**
```bash
# Login dengan:
Email: test@example.com
Password: password

# Atau create user baru:
php artisan tinker
>>> \App\Models\User::create(['name'=>'Admin', 'email'=>'admin@test.com', 'password'=>bcrypt('password123')])
```

## 7️⃣ File Penting

- 📄 `PROJECT_DOCUMENTATION.md` - Dokumentasi lengkap
- 📄 `IMPLEMENTATION_COMPLETE.md` - Detail implementasi
- 🗂️ `routes/web.php` - Semua routes
- 🎛️ `app/Http/Controllers/PerhitunganController.php` - MABAC logic
- 🗄️ `database/database.sqlite` - Database
- 🎨 `resources/views/` - Semua halaman

## 8️⃣ Command Useful

```bash
# List semua routes
php artisan route:list

# Jalankan migrations
php artisan migrate

# Seed database
php artisan db:seed

# Clear semua cache
php artisan cache:clear

# PHP Tinker (interactive shell)
php artisan tinker

# Buat user baru
php artisan tinker
>>> \App\Models\User::create(['name'=>'User', 'email'=>'user@test.com', 'password'=>bcrypt('pass')])
```

## 9️⃣ Tips

- 💡 Gunakan bobot yang sesuai dengan preferensi Anda
- 💡 Nilai bobot tidak perlu dijumlahkan 1, sistem auto-normalize
- 💡 Semakin tinggi bobot = semakin penting kriteria tersebut
- 💡 Admin bisa update bobot default di `admin/kriteria`
- 💡 Aplikasi fully responsive, bisa diakses dari HP

## 🔟 Support

Untuk info lebih lanjut, baca:
- `PROJECT_DOCUMENTATION.md` 
- `IMPLEMENTATION_COMPLETE.md`

---

**Selamat menggunakan SPK Pemilihan Mobil dengan Metode MABAC! 🎉**
