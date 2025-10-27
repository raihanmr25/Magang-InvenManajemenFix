# Inventory Management System

Sistem Manajemen Inventory dengan fitur lengkap untuk Pemerintah Kota Semarang.

## Fitur Utama

- ✅ **Manajemen Inventory**: CRUD lengkap untuk data barang dengan 20+ field
- ✅ **Barcode Generator**: Otomatis generate barcode dengan URL untuk akses langsung
- ✅ **PDF Sticker**: Export sticker barcode dalam format F4 (2 kolom)
- ✅ **Barcode Scan**: Scan barcode langsung redirect ke detail barang
- ✅ **CSV Import**: Upload bulk data melalui file CSV
- ✅ **Dashboard**: Statistik dan visualisasi dengan Chart.js
- ✅ **Authentication**: Sistem login untuk admin
- ✅ **REST API**: API untuk integrasi dengan aplikasi mobile
- ✅ **Responsive Design**: Menggunakan Tailwind CSS

## Database Fields

Sistem ini menyimpan data lengkap untuk setiap barang:
- Barcode (auto-generated)
- NIBAR
- Kode Barang
- Nama
- Spesifikasi
- Lokasi
- Pemakai
- Status
- Jabatan
- Identitas
- Alamat
- No. BAST & Tgl. BAST
- Dokumen
- No. Dokumen & Tgl. Dokumen
- Keterangan
- No. SIMDA
- No. Mesin
- Tahun

## Teknologi

- **Backend**: Laravel 12
- **Frontend**: Tailwind CSS, Vanilla JavaScript
- **Charts**: Chart.js
- **Barcode**: milon/barcode (DNS1D - CODE 128)
- **PDF**: TCPDF
- **Database**: SQLite (bisa diganti MySQL/PostgreSQL)

## Instalasi

### 1. Install Dependencies

```bash
composer install
npm install
```

### 2. Environment Setup

```bash
# Copy file .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Database Setup

```bash
# Jalankan migrasi
php artisan migrate

# (Opsional) Seed data dummy
php artisan db:seed
```

### 4. Storage Setup

```bash
# Buat symbolic link untuk storage
php artisan storage:link
```

### 5. Download Logo

Download logo Pemerintah Kota Semarang dan letakkan di:
```
public/assets/Lambang_Kota_Semarang.png
```

### 6. Buat Admin User

Jalankan di terminal Laravel Tinker atau langsung inject ke database:

```bash
php artisan tinker
```

```php
// Di tinker:
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('password123')
]);
```

Atau langsung via SQL:
```sql
INSERT INTO users (name, email, password, created_at, updated_at) 
VALUES (
    'Admin', 
    'admin@example.com', 
    '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5mKK9IWpJc.Ke', -- password: password123
    datetime('now'), 
    datetime('now')
);
```

### 7. Build Assets

```bash
npm run build
# atau untuk development
npm run dev
```

### 8. Jalankan Server

```bash
php artisan serve
```

Buka browser: `http://localhost:8000`

**Login:**
- Email: `admin@example.com`
- Password: `password123`

## API Endpoints

API tersedia di `/api/inventory`:

### Get Item by Barcode
```
GET /api/inventory/barcode/{barcode}
```

### Update Item by Barcode
```
PUT /api/inventory/barcode/{barcode}
Content-Type: application/json

{
    "nama": "Updated Name",
    "lokasi": "Updated Location",
    "status": "Baik"
}
```

### Get All Items
```
GET /api/inventory?per_page=20
```

### Search Items
```
GET /api/inventory/search?q=keyword
```

## Import CSV

Format CSV harus memiliki header (baris pertama):
```
nibar,kode_barang,nama_barang,spesifikasi_nama_barang,lokasi,nama_pemakai,status_pemakai,jabatan,nomor_identitas_pemakai,alamat_pemakai,bast_nomor,bast_tanggal,dokumen_nama,dokumen_nomor,dokumen_tanggal,keterangan,no_simda,new,tahun,no_mesin
```

**Contoh CSV:**
```csv
nibar,kode_barang,nama_barang,spesifikasi_nama_barang,lokasi,nama_pemakai,status_pemakai,jabatan,nomor_identitas_pemakai,alamat_pemakai,bast_nomor,bast_tanggal,dokumen_nama,dokumen_nomor,dokumen_tanggal,keterangan,no_simda,new,tahun,no_mesin
"10.020.010.012.000.600,00",01.03.02.02.01.01.004,Multi Purpose Vehicle (MPV),Toyota Innova,Jl. Mangunsarkoro No. 21 Semarang,"Dr. SUTRISNO, S.KM, MH.Kes",ASN,Kepala Dinas,3.37E+15,NGAGLIK LAMA NO.100 B,,,Pakta Integritas,,29 Agustus 2024,,1,H 1623 XA,2022,2GDD088765
"10.020.010.012.000.400,00",01.03.02.10.01.02.002,Laptop,Legion 7i intel Core i7,,,,,,,,,,,,,42,,2021,
```

**Catatan**: 
- Barcode akan di-generate otomatis oleh sistem
- Format tanggal Indonesia didukung: "29 Agustus 2024" atau YYYY-MM-DD
- Template CSV lengkap tersedia di: `storage/app/template_import.csv`

## Barcode Scan System 📱

Barcode yang di-generate **berisi URL** yang langsung redirect ke detail barang!

### Cara Kerja:
1. Scan barcode dengan aplikasi scanner atau kamera HP
2. Scanner akan membaca URL: `http://localhost:8000/inventory/scan/BRG000000001`
3. Browser otomatis terbuka dan menampilkan detail barang

### Manual Access:
```
http://localhost:8000/inventory/scan/{barcode}
```

Contoh:
```
http://localhost:8000/inventory/scan/BRG000000001
```

**Keuntungan:**
- ✅ **1-Step Access**: Scan langsung ke detail barang
- ✅ **No Manual Input**: Tidak perlu ketik atau cari
- ✅ **Mobile Friendly**: Bekerja dengan semua barcode scanner app
- ✅ **Production Ready**: Tinggal ganti APP_URL di .env

**Dokumentasi lengkap:** Lihat `BARCODE_URL_DOCUMENTATION.md`

## Generate PDF Sticker

Akses: **Cetak Stiker** di menu sidebar atau kunjungi:
```
http://localhost:8000/inventory/pdf/generate
```

PDF akan menampilkan:
- **Kolom Kiri**: Logo Kota Semarang, NIBAR, Kode Barang
- **Kolom Kanan**: Barcode gambar (berisi URL scan)
- **Format**: 2 kolom per baris, kertas F4

## Struktur Folder

```
app/
├── Http/
│   └── Controllers/
│       ├── Auth/
│       │   └── LoginController.php
│       ├── Api/
│       │   └── InventoryApiController.php
│       └── BarangPemakaianController.php
├── Models/
│   ├── User.php
│   └── BarangPemakaian.php
database/
├── migrations/
│   └── 2024_01_01_000003_create_barang_pemakaian_table.php
resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php
│   ├── auth/
│   │   └── login.blade.php
│   ├── inventory/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   └── dashboard.blade.php
routes/
├── web.php
└── api.php
```

## Troubleshooting

### Error: TCPDF tidak ditemukan
```bash
composer require tecnickcom/tcpdf
```

### Error: Barcode generator tidak ditemukan
```bash
composer require milon/barcode
```

### Error: Storage link tidak berfungsi
```bash
php artisan storage:link
```

### Error: Permission denied pada folder storage
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache

# Windows (jalankan sebagai Administrator)
# Atau pastikan folder dapat ditulis
```

## Development

Untuk development dengan hot reload:
```bash
npm run dev
php artisan serve
```

## Production

Build untuk production:
```bash
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## License

Sistem ini dibuat untuk Pemerintah Kota Semarang.

## Support

Untuk bantuan teknis, hubungi tim IT.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
