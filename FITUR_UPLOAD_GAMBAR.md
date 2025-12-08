# 📸 Fitur Upload Gambar Mobil - Documentation

## Overview
Fitur upload gambar mobil telah ditambahkan ke sistem SPK Pemilihan Mobil MABAC. Admin dapat mengunggah gambar saat membuat atau mengedit data mobil, dan gambar akan ditampilkan di halaman publik dan admin.

## 🎯 Fitur Utama

### 1. Upload Gambar saat Create Mobil
- Admin dapat mengunggah gambar baru saat menambah mobil
- Format yang didukung: JPG, PNG, GIF
- Ukuran maksimal: 5MB
- Gambar akan disimpan di `storage/app/public/mobils/`

### 2. Update Gambar saat Edit Mobil
- Admin dapat mengganti gambar lama dengan yang baru
- Gambar lama akan otomatis dihapus
- Atau kosongi input jika tidak ingin mengubah gambar

### 3. Delete Gambar
- Saat mobil dihapus, gambarnya juga akan dihapus otomatis

### 4. Tampilan Gambar
- **Di halaman list mobil**: Gambar ditampilkan dalam card (h-48, object-cover)
- **Di halaman detail mobil**: Gambar ditampilkan lebih besar (h-96, object-cover)
- **Di halaman admin**: Gambar thumbnail (h-12 w-16) dalam tabel
- **Fallback**: Jika tidak ada gambar, akan menampilkan emoji 🚗

## 📁 File Structure

### Tabel Mobils (Updated)
- Kolom `gambar` tetap ada (nullable)
- Sekarang menyimpan URL gambar: `/storage/mobils/timestamp_filename.jpg`

### Controllers
- **Admin\MobilController** - Tambah logic upload dan delete gambar
  - `store()` - Handle upload gambar saat create
  - `update()` - Handle upload/update gambar saat edit
  - `destroy()` - Handle delete gambar saat hapus mobil

### Migrations
- **2025_12_06_100843_add_gambar_to_mobils_table.php** - Normalize kolom gambar

### Views
- `admin/mobil/create.blade.php` - Tambah input file upload
- `admin/mobil/edit.blade.php` - Tambah input file upload + preview gambar
- `mobil/index.blade.php` - Tampilkan gambar dalam card, fallback emoji
- `mobil/show.blade.php` - Tampilkan gambar besar, fallback emoji
- `admin/mobil/index.blade.php` - Tambah kolom gambar dengan thumbnail

## 🚀 Cara Menggunakan

### Admin - Tambah Mobil dengan Gambar

1. Login ke dashboard admin: `http://localhost:8000/login`
   - Email: `test@example.com`
   - Password: `password`

2. Buka **Admin > Kelola Mobil > Tambah Mobil Baru**

3. Isi semua field:
   - Merek, Model, Tahun, Tipe
   - Harga, Fitur, Spesifikasi
   - **Gambar** (opsional) - Klik "Browse" dan pilih gambar JPG/PNG/GIF max 5MB

4. Klik **"Simpan Mobil"**

### Admin - Edit Mobil dan Ganti Gambar

1. Buka **Admin > Kelola Mobil**

2. Klik **"Edit"** pada mobil yang ingin diubah

3. Perhatikan preview gambar saat ini (jika ada)

4. Upload gambar baru atau kosongi untuk tidak mengubah

5. Klik **"Update Mobil"**

### Guest - Lihat Gambar Mobil

1. Buka halaman **Daftar Mobil**: `http://localhost:8000/mobil`
   - Lihat gambar di setiap card mobil
   - Atau emoji 🚗 jika belum ada gambar

2. Klik **"Lihat Detail"** untuk lihat gambar lebih besar di halaman detail

## 💾 Storage & Path

### Directory Structure
```
storage/
├── app/
│   └── public/
│       └── mobils/
│           ├── 1733533200_toyota-avanza.jpg
│           ├── 1733533201_honda-city.png
│           └── ...
```

### Public Access
Gambar dapat diakses via URL:
- `http://localhost:8000/storage/mobils/1733533200_toyota-avanza.jpg`

### Symbolic Link
- Symbolic link sudah dibuat otomatis dengan `php artisan storage:link`
- Di Windows: Junction link antara `public/storage` → `storage/app/public`

## 🛡️ Validation

### File Validation Rules
```php
'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
```

- **nullable**: File upload opsional
- **image**: Harus file gambar valid
- **mimes:jpeg,png,jpg,gif**: Format yang diizinkan
- **max:5120**: Maksimal 5MB (5120 KB)

### Error Messages
Jika ada error, akan ditampilkan pesan:
- "Gambar harus berupa file image"
- "Format gambar hanya boleh: jpeg, png, jpg, gif"
- "Ukuran gambar maksimal 5 MB"

## 🔄 File Management

### Upload Process
```
1. User select file → 
2. Validate (size, type) → 
3. Generate unique filename (timestamp + original name) →
4. Store ke storage/app/public/mobils/ →
5. Save URL ke database (format: /storage/mobils/filename)
```

### Update Process
```
1. Check if old image exists →
2. If yes, delete old image →
3. Upload new image →
4. Save new URL ke database
```

### Delete Process
```
1. Check if image exists →
2. Delete from filesystem →
3. Delete record dari database
```

## 📊 Database

### Kolom Gambar
```sql
gambar TEXT NULLABLE
```

### Value Example
```
/storage/mobils/1733533200_toyota-avanza.jpg
```

## 🖼️ Frontend Display

### Blade Template Examples

#### List View (card)
```blade
@if($mobil->gambar)
    <img src="{{ $mobil->gambar }}" alt="{{ $mobil->merk }}" class="w-full h-48 object-cover">
@else
    <div class="bg-linear-to-r from-blue-500 to-blue-600 h-48 flex items-center justify-center">
        <span class="text-white text-5xl">🚗</span>
    </div>
@endif
```

#### Detail View (large)
```blade
@if($mobil->gambar)
    <img src="{{ $mobil->gambar }}" alt="{{ $mobil->merk }}" class="w-full h-full object-cover rounded-lg">
@else
    <div class="bg-linear-to-r from-blue-500 to-blue-600 h-96 flex items-center justify-center">
        <span class="text-white text-8xl">🚗</span>
    </div>
@endif
```

#### Admin Table (thumbnail)
```blade
@if($mobil->gambar)
    <img src="{{ $mobil->gambar }}" alt="{{ $mobil->merk }}" class="h-12 w-16 object-cover rounded">
@else
    <span class="text-2xl">🚗</span>
@endif
```

## 🐛 Troubleshooting

### Q: Gambar tidak muncul setelah upload
**A:** 
- Pastikan `php artisan storage:link` sudah dijalankan
- Check file permissions di `storage/app/public/mobils/`
- Cek browser console untuk error

### Q: Upload error "File too large"
**A:** Gambar lebih dari 5MB, gunakan gambar yang lebih kecil

### Q: Upload error "Invalid file format"
**A:** Format file bukan JPG/PNG/GIF, gunakan format yang benar

### Q: Gambar tiba-tiba hilang
**A:** 
- Mungkin file dihapus manual dari folder `storage/app/public/mobils/`
- Upload ulang gambar dan jangan hapus manual

### Q: Symlink error di Windows
**A:**
- Pastikan jalankan terminal as Administrator
- Atau jalankan: `php artisan storage:link --force`

## 📝 Code Changes Summary

### Admin MobilController
- Tambah `use Illuminate\Support\Facades\Storage;`
- Update `store()` method - handle file upload
- Update `update()` method - handle file upload + delete old image
- Update `destroy()` method - delete image when mobil deleted

### Forms
- Form create & edit: Tambah `enctype="multipart/form-data"`
- Tambah input file: `<input type="file" name="gambar" accept="image/*">`
- Edit form: Tambah preview gambar saat ini

### Views
- Update tampilan di list, detail, dan admin table
- Conditional display: Gambar jika ada, emoji jika tidak

## ✅ Testing Checklist

- [ ] Upload gambar saat create mobil baru
- [ ] Lihat gambar di halaman daftar mobil
- [ ] Klik detail dan lihat gambar besar
- [ ] Edit mobil dan ganti gambar
- [ ] Lihat gambar lama tidak ada lagi
- [ ] Edit mobil tanpa ubah gambar (tetap sama)
- [ ] Hapus mobil dan cek gambar juga terhapus
- [ ] Test dengan file invalid (terlalu besar, format salah)
- [ ] Test dengan tidak upload gambar
- [ ] Lihat emoji 🚗 sebagai fallback

---

**Fitur upload gambar mobil siap digunakan! 🎉**
