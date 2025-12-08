# 🚀 Quick Start - Fitur Pemilihan Mobil

## 30 Detik Setup

Akses: **http://localhost:8000/perhitungan**

---

## 5 Scenario Cepat

### ① Analisis Semua Mobil (Paling Umum)
```
1. Buka /perhitungan
2. Semua mobil sudah tercentang ✓
3. Sesuaikan bobot kriteria (optional)
4. Klik "Hitung Rekomendasi"
5. Lihat ranking semua mobil
```

### ② Bandingkan 2 Mobil Spesifik (Tercepat)
```
1. Buka /perhitungan
2. Klik "Batal Pilih Semua"
3. Centang 2 mobil pilihan Anda
4. Klik "Hitung Rekomendasi"
5. Lihat perbandingan detail
```

### ③ Analisis 3-5 Mobil
```
1. Buka /perhitungan
2. Uncheck mobil yang tidak perlu
3. Biarkan 3-5 mobil tercentang
4. Klik "Hitung Rekomendasi"
5. Lihat hasil
```

### ④ Filter Berdasarkan Merk
```
1. Buka /perhitungan
2. Klik "Batal Pilih Semua"
3. Centang hanya mobil merk yang diinginkan
4. Sesuaikan bobot kriteria
5. Klik "Hitung Rekomendasi"
```

### ⑤ Atur Ulang Sesuai Preferensi
```
1. Buka /perhitungan
2. Update checkbox ke kombinasi baru
3. Ubah bobot kriteria sesuai prioritas
4. Klik "Hitung Rekomendasi"
5. Bandingkan dengan hasil sebelumnya
```

---

## 🎮 Button Controls

| Button | Fungsi | Ketika Gunakan |
|--------|--------|----------------|
| **Pilih Semua** | Centang semua mobil | Ingin reset ke default |
| **Batal Pilih Semua** | Uncentang semua | Ingin mulai dari nol |
| **Checkbox Individual** | Pilih/batal mobil spesifik | Perbandingan selektif |
| **Hitung Rekomendasi** | Submit form & hitung | Selesai setting |

---

## ⚡ Pro Tips

### Tip 1: Perbandingan Cepat
- Gunakan "Batal Pilih Semua" dulu
- Lalu centang hanya mobil yang ingin dibandingkan
- 2-3 mobil paling efisien untuk membandingkan

### Tip 2: Optimize Bobot
- Untuk mencari mobil murah: Naikkan bobot "Harga Baru"
- Untuk mencari mobil aman: Naikkan bobot "Fitur Keamanan"
- Untuk mobil nyaman: Naikkan bobot "Fitur Kenyamanan"
- Bobot tidak perlu dijumlahkan = 1, sistem otomatis normalize

### Tip 3: Batch Analysis
- Simpan 2-3 kombinasi favorit di notes Anda
- Analisis setiap kombinasi dengan bobot berbeda
- Bandingkan hasil antar kombinasi

### Tip 4: Mobile Friendly
- Layout checkbox responsive untuk semua ukuran
- Tap checkbox lebih mudah daripada klik di desktop
- Gambar mobil membantu identifikasi cepat

---

## ✅ Validasi Rules

```
✓ Minimal 2 mobil harus dipilih
✓ Maksimal semua mobil (tidak ada limit atas)
✓ Dapat pilih/unpilih kapan saja sebelum submit
✓ Alert otomatis jika < 2 mobil saat submit
```

---

## 📊 Hasil yang Diharapkan

### Untuk 2 Mobil
```
Rank 1: Mobil A (Score: 5.23)
Rank 2: Mobil B (Score: 3.17)
```
*Perbandingan detail, mudah lihat pemenang*

### Untuk 5 Mobil
```
Rank 1: Mobil C (Score: 6.45)
Rank 2: Mobil A (Score: 5.23)
Rank 3: Mobil B (Score: 3.17)
Rank 4: Mobil D (Score: 1.92)
Rank 5: Mobil E (Score: -0.84)
```
*Ranking komprehensif semua alternatif*

### Untuk Semua Mobil (10)
```
Rank 1-10: Semua mobil dianalisis
```
*Top 3 recommendation untuk pemakai umum*

---

## 🔧 Troubleshooting Cepat

| Problem | Solusi |
|---------|--------|
| **"Minimal pilih 2 mobil"** | Centang minimal 2 mobil sebelum submit |
| **Checkbox tidak update** | Reload halaman (Ctrl+R) |
| **Gambar tidak muncul** | Mobil tersebut belum punya gambar (normal) |
| **Hasil tidak berubah** | Pastikan checkbox/bobot benar-benar berubah |
| **Server error** | Restart server: `composer run dev` |

---

## 🎯 Checklist Sebelum Submit

- [ ] Minimal 2 mobil tercentang
- [ ] Bobot kriteria sudah diatur (optional)
- [ ] Semua field sudah review
- [ ] Ready untuk lihat hasil

---

## 📱 Screenshot Guide

### Sebelum (Tanpa Fitur)
```
❌ Semua mobil otomatis dihitung
❌ Tidak ada pilihan
❌ Perhitungan berat
```

### Setelah (Dengan Fitur)
```
✅ Checkbox untuk setiap mobil
✅ Tombol "Pilih Semua" / "Batal Pilih Semua"
✅ Counter real-time: "5 mobil dipilih"
✅ Gambar preview mobil
✅ Perhitungan lebih cepat
```

---

## 🚀 Next Steps

1. ✅ Buka `/perhitungan`
2. ✅ Pilih mobil yang ingin dianalisis
3. ✅ Sesuaikan bobot (optional)
4. ✅ Klik "Hitung Rekomendasi"
5. ✅ Lihat hasil ranking

---

**Last Updated**: 2025-12-08  
**Feature Version**: 1.0  
**Status**: ✅ Live & Ready  
