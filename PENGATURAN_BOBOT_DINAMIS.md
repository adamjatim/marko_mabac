# Dokumentasi Pengaturan Bobot Kriteria Dinamis

## Ringkasan Fitur

Fitur Pengaturan Bobot Kriteria Dinamis memungkinkan admin untuk mengatur nilai bobot setiap kriteria penilaian mobil secara dinamis. Sistem akan:

1. **Menggunakan bobot default** jika semua nilai input kosong
2. **Menghitung bobot otomatis** dari nilai input yang diberikan menggunakan formula normalisasi
3. **Validasi ketat** untuk memastikan semua kriteria terisi atau semua kosong
4. **Menyimpan bobot ke database** untuk digunakan dalam perhitungan MABAC

## Fitur-Fitur Utama

### 1. Input Nilai Bobot (Nilai L)
- User dapat mengisi nilai input (L) untuk setiap kriteria
- Nilai dapat berupa angka desimal positif
- Dapat dikosongkan semua untuk menggunakan default atau diisi semua untuk menggunakan nilai kustom

### 2. Perhitungan Otomatis
Sistem menggunakan formula normalisasi:
```
Bobot Kriteria (w) = Nilai Input (L) / Total Nilai Input (Σ L)
```

Contoh:
- Harga Baru: 9
- Harga Bekas: 5
- Fitur Keamanan: 6
- Fitur Kenyamanan: 5
- Efisiensi BBM: 2
- Performa: 4
- Pajak Tahunan: 7
- **Total: 38**

Maka bobot masing-masing:
- Harga Baru: 9/38 = 0.2368
- Harga Bekas: 5/38 = 0.1316
- Fitur Keamanan: 6/38 = 0.1579
- Fitur Kenyamanan: 5/38 = 0.1316
- Efisiensi BBM: 2/38 = 0.0526
- Performa: 4/38 = 0.1053
- Pajak Tahunan: 7/38 = 0.1842
- **Total: 1.0000**

### 3. Validasi Input

#### ✓ VALID - Semua Kosong
Ketika semua nilai input kosong:
- Sistem otomatis menggunakan bobot default dari setiap kriteria
- Nilai bobot default akan ditampilkan dengan label "Default"

#### ✓ VALID - Semua Terisi
Ketika semua nilai input terisi:
- Sistem menghitung bobot menggunakan formula normalisasi
- Total bobot selalu = 1.0000
- Data disimpan ke database
- Perhitungan MABAC menggunakan bobot ini

#### ✗ ERROR - Sebagian Kosong
Ketika ada kriteria kosong dan ada yang terisi:
- Sistem akan menampilkan error
- Pesan: "Kriteria yang kosong: [nama kriteria]. Harap isi semua kriteria atau kosongkan semuanya untuk menggunakan nilai default."
- Perhitungan tidak dapat dilakukan

#### ✗ ERROR - Nilai Input Invalid
Ketika ada nilai input ≤ 0:
- Sistem akan menampilkan error
- Pesan: "Nilai input harus lebih besar dari 0."

## Alur Penggunaan

### Step 1: Akses Pengaturan Bobot
- Dari Dashboard Admin → Klik "Pengaturan Bobot Kriteria"
- Atau dari Menu Kriteria → Klik "Pengaturan Bobot Kriteria"

### Step 2: Lihat Nilai Default
- Halaman menampilkan tabel dengan 6 kolom:
  - **Kode**: K1, K2, ... K7 (identitas kriteria)
  - **Nama Kriteria**: Nama lengkap kriteria
  - **Bobot Default**: Bobot bawaan sistem
  - **Nilai Input (L)**: Field untuk user input
  - **Perhitungan Bobot (w)**: Menampilkan formula "L/Total(L)"
  - **Hasil Bobot (Desimal)**: Hasil perhitungan bobot

### Step 3: Pilih Opsi

#### Opsi A: Gunakan Nilai Default
- Kosongkan semua field di "Nilai Input (L)"
- Klik tombol "Reset ke Default"
- Database akan dikosongkan, sistem otomatis gunakan bobot default

#### Opsi B: Gunakan Nilai Kustom
- Isi semua field "Nilai Input (L)" dengan nilai angka positif
- Klik tombol "Hitung Bobot" untuk preview
- Sistem akan menampilkan hasil perhitungan di bawah
- Jika valid (total = 1.0000), klik "Simpan Pengaturan Bobot"

### Step 4: Verifikasi
- Setelah simpan, halaman akan kembali dengan notifikasi sukses
- Bobot yang disimpan akan digunakan untuk perhitungan MABAC selanjutnya

## Struktur Database

### Tabel `bobot_kriterias`
```sql
CREATE TABLE bobot_kriterias (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    kriteria_id BIGINT NOT NULL (UNIQUE, FK),
    nilai_input DECIMAL(8,2) NULL,           -- Nilai L (value input)
    nilai_penyebut DECIMAL(10,2) DEFAULT 1, -- Total L
    hasil_bobot DECIMAL(5,4),                -- L / Total(L)
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## Integrasi dengan Perhitungan

Pada saat melakukan perhitungan pilihan mobil terbaik:

1. **Ambil bobot dari database**
   ```php
   $bobots = BobotKriteria::getActiveBobots();
   ```

2. **Jika ada di database, gunakan bobot tersebut**
   ```php
   if (isset($bobots[$kriteria->id])) {
       $weights[$kriteria->id] = (float) $bobots[$kriteria->id];
   }
   ```

3. **Jika tidak ada, gunakan bobot default**
   ```php
   else {
       $weights[$kriteria->id] = (float) $kriteria->bobot_default;
   }
   ```

4. **Lanjutkan perhitungan MABAC dengan bobot yang sudah ditentukan**

## API Methods

### BobotKriteria::hitungBobot(array $nilaiInputs)
Menghitung bobot dari array nilai input.

**Parameter:**
- `$nilaiInputs`: Array dengan key = kriteria_id, value = nilai input

**Return:**
- Array dengan struktur: `[kriteria_id => ['nilai_input' => ..., 'nilai_penyebut' => ..., 'hasil_bobot' => ..., 'adalah_default' => boolean]]`

**Throws:**
- Exception jika Ada kriteria kosong (sebagian)
- Exception jika Ada nilai input ≤ 0

### BobotKriteria::simpanBobot(array $hasilHitung)
Menyimpan hasil perhitungan bobot ke database.

**Parameter:**
- `$hasilHitung`: Array output dari `hitungBobot()`

**Return:**
- void

### BobotKriteria::getActiveBobots()
Mengambil semua bobot kriteria aktif dari database.

**Return:**
- Array dengan struktur: `[kriteria_id => hasil_bobot, ...]`

## Controller Methods

### KriteriaController::pengaturanBobot()
Menampilkan halaman pengaturan bobot dengan form input.

**Route:** `GET /admin/kriteria/pengaturan-bobot`

### KriteriaController::hitungBobot(Request $request)
Menghitung bobot dari input user (preview sebelum simpan).

**Route:** `POST /admin/kriteria/hitung-bobot`

**Input:**
- `nilai_input[kriteria_id]`: Nilai input untuk setiap kriteria

### KriteriaController::simpanBobot(Request $request)
Menyimpan bobot yang sudah dihitung ke database.

**Route:** `POST /admin/kriteria/simpan-bobot`

**Input:**
- `nilai_input[kriteria_id]`: Nilai input untuk setiap kriteria

**Redirect:** Ke halaman pengaturan bobot dengan pesan sukses/error

### KriteriaController::resetBobot()
Menghapus semua data bobot dari database (kembali ke default).

**Route:** `POST /admin/kriteria/reset-bobot`

**Redirect:** Ke halaman pengaturan bobot dengan pesan sukses

## Contoh Kasus Penggunaan

### Kasus 1: Pentingkan Harga
Admin ingin memberi bobot lebih tinggi untuk harga:
- Harga Baru: 15
- Harga Bekas: 10
- Fitur Keamanan: 5
- Fitur Kenyamanan: 3
- Efisiensi BBM: 4
- Performa: 3
- Pajak: 5
- **Total: 45**

Hasil bobot:
- Harga Baru: 15/45 = 0.3333 (naik dari 0.22)
- Harga Bekas: 10/45 = 0.2222 (naik dari 0.14)
- Dan seterusnya...

### Kasus 2: Pentingkan Performa
Admin ingin fokus pada performa dan efisiensi:
- Harga Baru: 5
- Harga Bekas: 5
- Fitur Keamanan: 6
- Fitur Kenyamanan: 6
- Efisiensi BBM: 12
- Performa: 15
- Pajak: 5
- **Total: 54**

Hasil bobot:
- Efisiensi BBM: 12/54 = 0.2222 (naik dari 0.18)
- Performa: 15/54 = 0.2778 (naik dari 0.12)
- Dan seterusnya...

## Testing

### Test Scenario 1: Valid Default
```
1. Buka halaman pengaturan bobot
2. Kosongkan semua field (atau biarkan kosong)
3. Klik "Reset ke Default"
4. ✓ Database dikosongkan
5. ✓ Perhitungan MABAC menggunakan bobot default
```

### Test Scenario 2: Valid Custom
```
1. Buka halaman pengaturan bobot
2. Isi semua field dengan nilai > 0
3. Klik "Hitung Bobot"
4. ✓ Preview menampilkan perhitungan
5. ✓ Total bobot = 1.0000
6. Klik "Simpan Pengaturan Bobot"
7. ✓ Konfirmasi notifikasi sukses
8. ✓ Data tersimpan di database
```

### Test Scenario 3: Invalid Partial Fill
```
1. Buka halaman pengaturan bobot
2. Isi sebagian field, kosongkan sebagian lainnya
3. Klik "Hitung Bobot"
4. ✓ Muncul error: "Kriteria yang kosong: ..."
5. ✓ Tidak ada data yang disimpan
```

## Notes

- Bobot dapat diubah kapan saja
- Setiap perhitungan MABAC menggunakan bobot yang sedang aktif di database
- Jika database kosong (tidak ada bobot disimpan), sistem otomatis menggunakan default
- Total bobot dari nilai input selalu = 1.0000 (terjamin dengan formula normalisasi)
- Validasi dilakukan di controller dan di client-side (JavaScript) untuk UX yang lebih baik
