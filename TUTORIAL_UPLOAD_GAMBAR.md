# 🎓 TUTORIAL: Cara Upload Gambar Mobil

Panduan step-by-step menggunakan fitur upload gambar di SPK Pemilihan Mobil MABAC.

## 📋 Prerequisites

- Server sudah berjalan: `composer run dev`
- Database sudah ter-seed dengan data
- Symbolic link sudah dibuat: `php artisan storage:link`

## 🎯 Skenario 1: Upload Gambar Mobil Baru

### Step 1: Login Admin
```
URL: http://localhost:8000/login
Email: test@example.com
Password: password
```

### Step 2: Pergi ke Menu Kelola Mobil
```
Klik: Navbar > Kelola Data Mobil
atau: http://localhost:8000/admin/mobil
```

### Step 3: Klik Tombol "Tambah Mobil Baru"
```
Button hijau di sebelah judul "Kelola Data Mobil"
atau: http://localhost:8000/admin/mobil/create
```

### Step 4: Isi Form Mobil

#### Bagian Identitas
- **Merek**: Toyota
- **Model**: Yaris
- **Tahun**: 2024
- **Tipe**: City Car

#### Bagian Harga
- **Harga Baru**: 225000000
- **Harga Jual Kembali**: 168750000
- **Pajak**: 2250000

#### Bagian Spesifikasi
- **Fitur Keamanan**: 6
- **Fitur Kenyamanan**: 8
- **Jarak Tempuh**: 15.5
- **Kapasitas Mesin**: 1500

#### Bagian Gambar (PENTING)
```
1. Klik pada field "Gambar Mobil"
2. Browse dan pilih file:
   - Format: JPG, PNG, atau GIF
   - Ukuran: Maksimal 5MB
   - Contoh: toyota-yaris.jpg
3. Klik "Open" / "Select"
4. Lihat nama file muncul di field
```

### Step 5: Klik "Simpan Mobil"
```
Button hijau "Simpan Mobil" di bagian bawah form
```

### Step 6: Verifikasi Upload Berhasil
```
1. Redirect ke halaman list mobil
2. Success message: "Mobil berhasil ditambahkan"
3. Mobil baru muncul di tabel dengan gambar thumbnail
4. Gambar sudah tersimpan di: storage/app/public/mobils/
```

### Step 7: Lihat Gambar di Halaman Public
```
1. Logout atau buka halaman publik: http://localhost:8000/mobil
2. Cari mobil yang baru ditambah (Toyota Yaris)
3. Lihat gambar muncul dalam card
4. Klik "Lihat Detail" untuk melihat gambar lebih besar
```

## 🔄 Skenario 2: Edit Gambar Mobil Lama

### Step 1: Pergi ke Admin Kelola Mobil
```
http://localhost:8000/admin/mobil
```

### Step 2: Klik "Edit" pada Mobil yang Ingin Diubah
```
Cari baris mobil yang ingin diubah
Klik link "Edit" di kolom Aksi
```

### Step 3: Lihat Preview Gambar Lama
```
Form akan menampilkan preview gambar saat ini (jika ada)
Contoh: Toyota Avanza dengan gambar lama
```

### Step 4: Upload Gambar Baru (Optional)
```
Pilihan A: Ubah Gambar
- Klik field "Gambar Mobil"
- Pilih file gambar baru
- Gambar lama akan otomatis dihapus

Pilihan B: Tetap Gambar Lama
- Kosongi field "Gambar Mobil"
- Klik "Update Mobil"
- Gambar lama tetap ada
```

### Step 5: Klik "Update Mobil"
```
Button hijau di bagian bawah form
```

### Step 6: Verifikasi Update Berhasil
```
1. Success message: "Mobil berhasil diubah"
2. Gambar baru muncul di list (jika di-update)
3. Gambar lama sudah dihapus dari storage
4. URL gambar di database sudah berubah
```

## ❌ Skenario 3: Hapus Mobil beserta Gambarnya

### Step 1: Pergi ke Admin Kelola Mobil
```
http://localhost:8000/admin/mobil
```

### Step 2: Klik "Hapus" pada Mobil yang Ingin Dihapus
```
Cari baris mobil yang ingin dihapus
Klik link "Hapus" di kolom Aksi (merah)
```

### Step 3: Confirm Deletion
```
Dialog akan muncul: "Yakin ingin menghapus?"
Klik "OK" untuk confirm
```

### Step 4: Verifikasi Delete Berhasil
```
1. Success message: "Mobil berhasil dihapus"
2. Mobil menghilang dari tabel
3. Gambar juga otomatis dihapus dari: storage/app/public/mobils/
4. Database record hilang
```

## 📸 Tips Upload Gambar

### Ukuran Gambar yang Baik
- **Recommended**: 800x600px atau lebih besar
- **Minimum**: 400x300px
- **Format**: JPG (lebih kecil), PNG (better quality)
- **File Size**: 2-5MB (balance quality & loading)

### Persiapan Gambar
```bash
# Compress gambar dengan tool online:
# - TinyPNG.com
# - Compressor.io
# - ImageOptim (Mac)
# - FileOptimizer (Windows)

# Atau dengan command line:
# ImageMagick: convert input.jpg -resize 800x600 -quality 85 output.jpg
```

### Naming Convention
```
Format: [Brand]-[Model]-[Year].jpg

Contoh:
✅ Toyota-Avanza-2024.jpg
✅ Honda-City-2024.jpg
✅ BMW-X5-2024.jpg
```

## 🎨 Tampilan Gambar di Berbagai Halaman

### Halaman Public List (/mobil)
```
- Ukuran: 768px × 192px (w-full h-48)
- Aspect Ratio: Landscape (4:1)
- Effect: object-cover (crop jika tidak sesuai)
- Rounded: lg (8px)
- Shadow: md (hover effect)
```

### Halaman Public Detail (/mobil/{id})
```
- Ukuran: Full width × 384px (w-full h-96)
- Aspect Ratio: Landscape (3:2)
- Effect: object-cover
- Rounded: lg (8px)
- Shadow: md
```

### Halaman Admin List (/admin/mobil)
```
- Ukuran: 64px × 64px (h-12 w-16)
- Thumbnail: Compact display
- Aspect Ratio: 16:12 (portrait)
- Rounded: Rounded corners
```

## ⚠️ Error Handling

### Error: File Terlalu Besar
```
Error: "Ukuran gambar maksimal 5 MB"

Solusi:
1. Compress gambar terlebih dahulu
2. Gunakan tool online seperti TinyPNG
3. Upload lagi dengan file yang lebih kecil
```

### Error: Format File Tidak Diterima
```
Error: "Format gambar hanya boleh: jpeg, png, jpg, gif"

Solusi:
1. Pastikan file adalah gambar (bukan text, dll)
2. Convert file ke JPG/PNG/GIF dengan:
   - Online: Convertio.co
   - Desktop: Photoshop, Paint
   - Command: ImageMagick
3. Upload lagi
```

### Error: Upload Gagal (Unexpected)
```
Solusi:
1. Clear cache: php artisan cache:clear
2. Check storage permissions
3. Verify storage link exists
4. Restart server: composer run dev
5. Try upload lagi
```

### Gambar Tidak Muncul di Halaman
```
Solusi:
1. Check browser cache (Ctrl+Shift+Delete)
2. Verify file di storage: storage/app/public/mobils/
3. Check public/storage symlink exists
4. Run: php artisan storage:link --force
5. Restart browser
```

## 💾 File Storage Locations

### Original Upload Folder
```
storage/app/public/mobils/
├── 1733533200_toyota-avanza.jpg (100KB)
├── 1733533201_honda-city.png (120KB)
├── 1733533202_suzuki-ertiga.jpg (95KB)
└── ... (lebih banyak file)
```

### Public Access Link
```
Symbolic link: public/storage → storage/app/public
```

### Direct File URL
```
http://localhost:8000/storage/mobils/1733533200_toyota-avanza.jpg
```

### Database Storage
```
Tabel: mobils
Kolom: gambar
Value: /storage/mobils/1733533200_toyota-avanza.jpg
```

## 🔍 Troubleshooting

### "Symlink doesn't exist"
```bash
# Run this command:
php artisan storage:link --force

# Or manual create:
# Windows (Admin CMD): mklink /J public\storage storage\app\public
# Linux/Mac: ln -s storage/app/public public/storage
```

### "Permission Denied"
```bash
# Fix file permissions:
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### "Disk not found"
```
Check: config/filesystems.php
Ensure 'public' disk is configured correctly
```

### "Duplicate file upload"
```
Each upload gets unique filename (timestamp + original name)
Automatic conflict prevention
```

## ✅ Quality Checklist

Sebelum upload gambar, pastikan:

```
☑️ File adalah gambar valid (JPG/PNG/GIF)
☑️ File size ≤ 5MB
☑️ Resolusi minimum 400x300px
☑️ Gambar menampilkan mobil dengan jelas
☑️ Foto dari sudut 45 derajat (terbaik)
☑️ Lighting bagus (tidak gelap/silhouette)
☑️ Background neutral atau clean
☑️ Tidak ada watermark (unless branding)
☑️ Aspect ratio landscape (lebih lebar dari tinggi)
☑️ File name jelas (misal: toyota-avanza.jpg)
```

## 📞 Support

Jika ada masalah:

1. Baca: `FITUR_UPLOAD_GAMBAR.md` - Full documentation
2. Baca: `UPLOAD_GAMBAR_IMPLEMENTATION.md` - Technical details
3. Check: Server logs - `storage/logs/laravel.log`
4. Try: Clear cache & restart server
5. Ask: Contact developer/support

---

**Selamat! Anda sekarang ahli dalam menggunakan fitur upload gambar mobil! 🎉**
