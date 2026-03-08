# 🚀 QUICK START - CARA MENGGUNAKAN PENGATURAN BOBOT

## ✅ Verifikasi (Optional)

Untuk memastikan semua bekerja, jalankan:
```bash
php artisan bobot:debug
```

Jika output menampilkan "SEMUA TEST BERHASIL ✓" berarti semua siap! ✅

---

## 🎯 Step-by-Step Cara Menggunakan

### Step 1: Login ke Admin
```
1. Buka browser
2. Masuk ke: http://localhost:8000/login
3. Login dengan admin account Anda
4. Klik Dashboard
```

### Step 2: Buka Pengaturan Bobot
**Option A - Via Dashboard:**
```
Dashboard → Tombol "⚙️ Pengaturan Bobot Kriteria"
```

**Option B - Via Menu Kriteria:**
```
Menu Kriteria → Tombol "⚙️ Pengaturan Bobot Kriteria"
```

### Step 3: Pilih Salah Satu Opsi

#### Opsi 1️⃣: Gunakan Bobot Default
```
1. Biarkan semua field kosong
2. Klik "Reset ke Default"
3. Konfirmasi dialog
4. SELESAI! Sistem pakai bobot default (0.22, 0.14, 0.16, dst)
```

#### Opsi 2️⃣: Gunakan Bobot Custom
```
1. Isi SEMUA 7 field dengan nilai angka positif
   Contoh:
   - Harga Baru: 9
   - Harga Bekas: 5
   - Fitur Keamanan: 6
   - Fitur Kenyamanan: 5
   - Efisiensi BBM: 2
   - Performa: 4
   - Pajak: 7

2. Klik "Hitung Bobot" (untuk preview)
   Lihat hasil di bawah:
   - Harga Baru: 9/38 = 0.2368
   - Harga Bekas: 5/38 = 0.1316
   - ... dst ...
   - TOTAL: 1.0000 ✓

3. Jika OK, klik "Simpan Pengaturan Bobot"

4. SELESAI! Bobot baru sekarang aktif untuk perhitungan MABAC
```

### Step 4: Verify Bobot Digunakan
```
1. Buka menu "Perhitungan"
2. Pilih 2+ mobil
3. Klik "Hitung MABAC"
4. Lihat "Weights" di hasil
5. Pastikan sesuai dengan bobot yang Anda set
```

---

## ⚠️ Aturan Penting

### ✅ BOLEH
- Kosongkan semua 7 field → Gunakan default
- Isi semua 7 field dengan nilai > 0 → Hitung custom
- Reset penuh jika mau kembali ke default

### ❌ TIDAK BOLEH
- Isi sebagian (misalnya 3 field dari 7) → Akan ERROR
- Input nilai 0 atau negatif → Akan ERROR
- Isi field dengan text/huruf → Akan ERROR

---

## 📊 Contoh Nilai Input

### Scenario 1: Pentingkan Harga
```
K1 Harga Baru: 20
K2 Harga Bekas: 10
K3 Fitur Keamanan: 5
K4 Fitur Kenyamanan: 5
K5 Efisiensi BBM: 5
K6 Performa: 5
K7 Pajak: 5
Total: 55 → Bobot Harga Baru 20/55 = 0.36 (naik dari 0.22)
```

### Scenario 2: Balanced (Default)
```
K1-K7: Kosongkan semua → Pakai default 0.22, 0.14, 0.16, 0.08, 0.18, 0.12, 0.10
```

### Scenario 3: Pentingkan Performa
```
K1 Harga Baru: 5
K2 Harga Bekas: 5
K3 Fitur Keamanan: 6
K4 Fitur Kenyamanan: 6
K5 Efisiensi BBM: 10
K6 Performa: 15 ← Besar nilainya
K7 Pajak: 5
Total: 52 → Bobot Performa 15/52 = 0.288 (naik dari 0.12)
```

---

## 🎨 Interface Guide

### Form Input
```
┌─────────────────────────────────────────────┐
│ Kode │ Nama Kriteria │ Default │ Input(L) │ ...
├─────────────────────────────────────────────┤
│ K1   │ Harga Baru   │  0.22  │ [INPUT]  │ ...
│ K2   │ Harga Bekas  │  0.14  │ [INPUT]  │ ...
│ K3   │ Fitur Keam.  │  0.16  │ [INPUT]  │ ...
│ K4   │ Fitur Keny.  │  0.08  │ [INPUT]  │ ...
│ K5   │ Efisiensi    │  0.18  │ [INPUT]  │ ...
│ K6   │ Performa     │  0.12  │ [INPUT]  │ ...
│ K7   │ Pajak        │  0.10  │ [INPUT]  │ ...
└─────────────────────────────────────────────┘

Tombol: [Hitung Bobot] [Simpan] [Reset] [Batal]
```

### Preview Hasil
```
Total Nilai Input: 38
Total Bobot: 1.0000 ✓

K1: 0.2368 (23.68%)
K2: 0.1316 (13.16%)
K3: 0.1579 (15.79%)
K4: 0.1316 (13.16%)
K5: 0.0526 (5.26%)
K6: 0.1053 (10.53%)
K7: 0.1842 (18.42%)
```

---

## 🆘 Troubleshooting

### Problem: Halaman tidak terbuka
**Solution:**
- Cek route: `php artisan route:list | grep "admin.kriteria.pengaturan"`
- Pastikan sudah di-login sebagai admin
- Clear cache: `php artisan cache:clear`

### Problem: Error "Kriteria yang kosong"
**Solution:**
- Pastikan isi SEMUA 7 field
- Jangan ada yang dikosongkan sebagian
- Atau kosongkan SEMUA untuk pakai default

### Problem: Total bobot tidak 1.0000
**Solution:**
- Jangan mungkin - formula mathematis selalu 1.0000
- Ini bukan kebetulan, ini jaminan math!

### Problem: Bobot tidak berpengaruh pada perhitungan
**Solution:**
- Cek apakah bobot sudah disimpan via "Simpan Pengaturan Bobot"
- Buka Perhitungan → lihat "Weights" apakah sesuai
- Jika tidak, kemungkinan belum disimpan dengan baik

---

## 📞 Bantuan Lebih Lanjut

Jika masih ada masalah, jalankan debug command:
```bash
php artisan bobot:debug
```

Abaikan pesan teknis, yang penting output akhir:
- ✓ PASS semua = Semuanya OK, silakan naik ke admin panel
- ✗ FAIL ada = Ada masalah, report ke developer

---

## ✨ Fitur Bonus

1. **Preview Otomatis**: Saat klik "Hitung Bobot", langsung lihat hasilnya
2. **Reset Cepat**: Satu klik balik ke default
3. **Validasi Ketat**: Tidak bisa input yang invalid
4. **Responsive Design**: Berfungsi di mobile, tablet, desktop
5. **Clear Error Message**: Tahu persis apa yang salah

---

**Version**: 1.0  
**Status**: ✅ READY TO USE  
**Last Updated**: 2026-03-08  

---

> **Tips**: Mulai dari Opsi 1 (Default) dulu untuk test, baru coba Opsi 2 (Custom) setelah paham caranya!
