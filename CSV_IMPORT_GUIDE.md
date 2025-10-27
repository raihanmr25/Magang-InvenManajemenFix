# PANDUAN IMPORT CSV
## Sistem Manajemen Inventory - Pemerintah Kota Semarang

---

## 📋 Format CSV

### Header CSV (Baris Pertama - Wajib)
```csv
nibar,kode_barang,nama_barang,spesifikasi_nama_barang,lokasi,nama_pemakai,status_pemakai,jabatan,nomor_identitas_pemakai,alamat_pemakai,bast_nomor,bast_tanggal,dokumen_nama,dokumen_nomor,dokumen_tanggal,keterangan,no_simda,new,tahun,no_mesin
```

### Mapping Kolom CSV ke Database

| Kolom CSV | Kolom Database | Keterangan |
|-----------|----------------|------------|
| nibar | nibar | Nomor Inventaris Barang |
| kode_barang | kode_barang | Kode Barang |
| nama_barang | nama | Nama Barang (WAJIB) |
| spesifikasi_nama_barang | spesifikasi | Spesifikasi Barang |
| lokasi | lokasi | Lokasi Barang |
| nama_pemakai | pemakai | Nama Pemakai |
| status_pemakai | status | Status Pemakai (ASN, Non-ASN, dll) |
| jabatan | jabatan | Jabatan Pemakai |
| nomor_identitas_pemakai | identitas | Nomor Identitas (KTP/NIK) |
| alamat_pemakai | alamat | Alamat Pemakai |
| bast_nomor | no_bast | Nomor BAST |
| bast_tanggal | tgl_bast | Tanggal BAST |
| dokumen_nama | dokumen | Nama Dokumen |
| dokumen_nomor | no_dok | Nomor Dokumen |
| dokumen_tanggal | tgl_dok | Tanggal Dokumen |
| keterangan | keterangan | Keterangan Tambahan |
| no_simda | no_simda | Nomor SIMDA |
| new | new | Field Tambahan (opsional) |
| tahun | tahun | Tahun Perolehan |
| no_mesin | no_mesin | Nomor Mesin/Serial Number |

---

## 📝 Contoh Data CSV

### Contoh 1: Kendaraan dengan Data Lengkap
```csv
nibar,kode_barang,nama_barang,spesifikasi_nama_barang,lokasi,nama_pemakai,status_pemakai,jabatan,nomor_identitas_pemakai,alamat_pemakai,bast_nomor,bast_tanggal,dokumen_nama,dokumen_nomor,dokumen_tanggal,keterangan,no_simda,new,tahun,no_mesin
"10.020.010.012.000.600,00",01.03.02.02.01.01.004,Multi Purpose Vehicle (MPV),Toyota Innova,Jl. Mangunsarkoro No. 21 Semarang,"Dr. SUTRISNO, S.KM, MH.Kes",ASN,Kepala Dinas,3374010101010001,NGAGLIK LAMA NO.100 B RT.02 RW.05 BENDUNGAN,,,Pakta Integritas,,29 Agustus 2024,,1,H 1623 XA,2022,2GDD088765
```

### Contoh 2: Laptop Tanpa Pemakai
```csv
nibar,kode_barang,nama_barang,spesifikasi_nama_barang,lokasi,nama_pemakai,status_pemakai,jabatan,nomor_identitas_pemakai,alamat_pemakai,bast_nomor,bast_tanggal,dokumen_nama,dokumen_nomor,dokumen_tanggal,keterangan,no_simda,new,tahun,no_mesin
"10.020.010.012.000.400,00",01.03.02.10.01.02.002,Laptop,Legion 7i intel Core i7,,,,,,,,,,,,,42,,2021,
```

### Contoh 3: Motor dengan Data Lengkap
```csv
nibar,kode_barang,nama_barang,spesifikasi_nama_barang,lokasi,nama_pemakai,status_pemakai,jabatan,nomor_identitas_pemakai,alamat_pemakai,bast_nomor,bast_tanggal,dokumen_nama,dokumen_nomor,dokumen_tanggal,keterangan,no_simda,new,tahun,no_mesin
"10.020.101.020.000.000,00",01.03.02.02.01.04.001,Sepeda Motor,YAMAHA NEW MIO M3 125 BLUE CORE CW,Jl. Mangunsarkoro No. 21 Semarang,"AUGUS TINEKE, SH",ASN,Kepala UPTD,3374010101010001,JL. LUMBUNGSARI VI NO 10 SMG,,,Pakta Integritas,,29 Agustus 2024,,25,H 6732 XA,2015,E3R2E0598503
```

---

## ⚙️ Fitur Import

### 1. Auto-Generate Barcode
- Sistem akan otomatis membuat barcode unik untuk setiap barang
- Format: `BRG` + 9 digit angka (contoh: BRG000000001)
- Tidak perlu menambahkan kolom barcode di CSV

### 2. Format Tanggal Fleksibel

**Format Indonesia (Didukung):**
- `29 Agustus 2024`
- `15 Januari 2023`
- `1 Mei 2022`

**Format Standard:**
- `2024-08-29`
- `2023-01-15`
- `2022-05-01`

**Bulan yang Didukung:**
- Januari, Februari, Maret, April, Mei, Juni
- Juli, Agustus, September, Oktober, November, Desember

### 3. Handling Data Kosong
- Kolom kosong akan disimpan sebagai `NULL`
- Nilai `N/A` atau `n/a` akan disimpan sebagai `NULL`
- Hanya kolom `nama_barang` yang WAJIB diisi

### 4. Validasi
- System akan memvalidasi jumlah kolom per baris
- Error akan ditampilkan per baris yang gagal
- Data yang valid tetap akan diimport

---

## 📥 Cara Import CSV

### Langkah 1: Persiapkan File CSV
1. Buka Excel atau aplikasi spreadsheet
2. Masukkan header sesuai format di atas
3. Isi data sesuai contoh
4. Save As → CSV (Comma delimited) (*.csv)

### Langkah 2: Import di Aplikasi
1. Login ke aplikasi
2. Klik menu "Data Barang"
3. Klik tombol "Import CSV"
4. Pilih file CSV yang sudah disiapkan
5. Klik "Import"
6. Tunggu proses selesai

### Langkah 3: Verifikasi Hasil
- Sistem akan menampilkan jumlah data yang berhasil diimport
- Jika ada error, akan ditampilkan detail error per baris
- Cek data yang sudah diimport di tabel

---

## ✅ Tips Sukses Import

### 1. Gunakan Template
- Download template dari: `storage/app/template_import.csv`
- Copy format dan isi dengan data Anda
- Jangan ubah nama header kolom

### 2. Encoding File
- Gunakan encoding UTF-8 untuk karakter Indonesia
- Di Excel: Save As → More Options → Encoding: UTF-8

### 3. Format Data
- **Nomor dengan koma**: Gunakan tanda kutip ganda
  ```csv
  "10.020.010.012.000.600,00"
  ```
- **Nama dengan koma**: Gunakan tanda kutip ganda
  ```csv
  "Dr. SUTRISNO, S.KM, MH.Kes"
  ```
- **Scientific Notation (Excel)**: Gunakan kutip atau format sebagai text
  ```csv
  3374010101010001  atau  '3374010101010001
  ```

### 4. Testing
- Test import dengan 5-10 data terlebih dahulu
- Cek hasilnya di aplikasi
- Jika sudah benar, import data lengkap

---

## ❌ Common Errors & Solutions

### Error 1: "Jumlah kolom tidak sesuai"
**Penyebab:** Jumlah kolom di baris data tidak sama dengan header

**Solusi:**
- Pastikan setiap baris memiliki 20 kolom
- Jika ada kolom kosong, tetap sisakan pemisah koma
- Contoh benar: `,,,,` (4 kolom kosong)

### Error 2: Tanggal tidak valid
**Penyebab:** Format tanggal tidak dikenali

**Solusi:**
- Gunakan format: `DD Bulan YYYY` (29 Agustus 2024)
- Atau gunakan: `YYYY-MM-DD` (2024-08-29)
- Pastikan nama bulan dalam Bahasa Indonesia

### Error 3: Karakter aneh/corrupted
**Penyebab:** Encoding file salah

**Solusi:**
- Save CSV dengan encoding UTF-8
- Di Notepad++: Encoding → Convert to UTF-8
- Di Excel: Save As → CSV UTF-8 (Comma delimited)

### Error 4: Scientific Notation di Excel
**Penyebab:** Excel auto-convert angka panjang (NIK, NIBAR)

**Solusi:**
- Format cell sebagai Text sebelum input
- Atau tambahkan apostrophe di depan: `'3374010101010001`
- Atau wrap dengan quotes: `"3374010101010001"`

### Error 5: Data tidak lengkap
**Penyebab:** Kolom required kosong

**Solusi:**
- Minimal `nama_barang` harus diisi
- Kolom lain boleh kosong

---

## 🔧 Advanced: Import Bulk Data Besar

### Untuk File > 1000 Baris

**Option 1: Split File**
```
Split CSV menjadi beberapa file (masing-masing 500-1000 rows)
Import satu per satu
```

**Option 2: Increase Timeout**
Edit `.env`:
```env
MAX_EXECUTION_TIME=300
MEMORY_LIMIT=512M
```

**Option 3: Command Line (Advanced)**
```powershell
php artisan tinker
```
```php
$csvFile = 'path/to/large-file.csv';
// Custom import script
```

---

## 📊 Sample Template

File template tersedia di:
```
storage/app/template_import.csv
```

Atau copy dari attachment yang Anda berikan.

---

## 📞 Support

Jika mengalami masalah saat import:
1. Cek format CSV sesuai panduan ini
2. Test dengan data sample terlebih dahulu
3. Lihat error message yang muncul
4. Hubungi tim IT untuk bantuan

---

## 📈 Monitoring Import

Setelah import berhasil:
- ✅ Cek jumlah data di Dashboard
- ✅ Verifikasi barcode ter-generate otomatis
- ✅ Cek data detail di "Data Barang"
- ✅ Test generate PDF Stiker
- ✅ Test API endpoint

---

**Created for:** Pemerintah Kota Semarang  
**Last Updated:** October 2024  
**Version:** 1.0
