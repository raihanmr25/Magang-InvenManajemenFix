# API DOCUMENTATION
# Inventory Management System

Base URL: `http://localhost:8000/api`

## Authentication

API saat ini tidak menggunakan authentication. Untuk production, disarankan menambahkan Laravel Sanctum atau Passport.

---

## Endpoints

### 1. Get Item by Barcode

Mendapatkan detail barang berdasarkan barcode.

**Endpoint:** `GET /inventory/barcode/{barcode}`

**Parameters:**
- `barcode` (string, required) - Barcode barang

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "barcode": "BRG000000001",
        "nibar": "NIB001",
        "kode_barang": "KBR001",
        "nama": "Laptop Dell Latitude 5420",
        "spesifikasi": "Intel Core i5-1145G7 8GB RAM 256GB SSD",
        "lokasi": "Ruang IT Lantai 3",
        "pemakai": "Budi Santoso",
        "status": "Baik",
        "jabatan": "Staff IT",
        "identitas": "3374010101010001",
        "alamat": "Jl. Pemuda No. 148 Semarang",
        "no_bast": "BAST-IT-001",
        "tgl_bast": "2024-01-15",
        "dokumen": "Dokumen Pembelian",
        "no_dok": "DOK-2024-001",
        "tgl_dok": "2024-01-10",
        "keterangan": "Laptop untuk keperluan administratif",
        "no_simda": "SIMDA-2024-001",
        "no_mesin": "SN123456789",
        "tahun": "2024",
        "created_at": "2024-10-10T10:00:00.000000Z",
        "updated_at": "2024-10-10T10:00:00.000000Z"
    }
}
```

**Response Error (404):**
```json
{
    "success": false,
    "message": "Barang tidak ditemukan"
}
```

**Example Request:**
```bash
curl -X GET http://localhost:8000/api/inventory/barcode/BRG000000001
```

---

### 2. Update Item by Barcode

Mengupdate data barang berdasarkan barcode.

**Endpoint:** `PUT /inventory/barcode/{barcode}`

**Parameters:**
- `barcode` (string, required) - Barcode barang

**Request Body:**
```json
{
    "nibar": "NIB001",
    "kode_barang": "KBR001",
    "nama": "Laptop Dell Updated",
    "spesifikasi": "Updated specs",
    "lokasi": "Ruang Baru",
    "pemakai": "John Doe",
    "status": "Rusak Ringan",
    "jabatan": "Manager",
    "identitas": "3374010101010001",
    "alamat": "Jl. Pemuda No. 150",
    "no_bast": "BAST-NEW-001",
    "tgl_bast": "2024-10-10",
    "dokumen": "DOK",
    "no_dok": "DOK-001",
    "tgl_dok": "2024-10-09",
    "keterangan": "Updated",
    "no_simda": "SIM-001",
    "no_mesin": "SN987654",
    "tahun": "2024"
}
```

**Note:** Semua field opsional. Hanya field yang dikirim yang akan diupdate.

**Response Success (200):**
```json
{
    "success": true,
    "message": "Barang berhasil diupdate",
    "data": {
        "id": 1,
        "barcode": "BRG000000001",
        "nama": "Laptop Dell Updated",
        "lokasi": "Ruang Baru",
        "status": "Rusak Ringan",
        ...
    }
}
```

**Response Error (404):**
```json
{
    "success": false,
    "message": "Barang tidak ditemukan"
}
```

**Example Request:**
```bash
curl -X PUT http://localhost:8000/api/inventory/barcode/BRG000000001 \
  -H "Content-Type: application/json" \
  -d '{
    "lokasi": "Ruang Server",
    "status": "Baik"
  }'
```

---

### 3. Get All Items (with Pagination)

Mendapatkan semua barang dengan pagination.

**Endpoint:** `GET /inventory`

**Query Parameters:**
- `per_page` (integer, optional, default: 20) - Jumlah item per halaman

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "barcode": "BRG000000001",
                "nibar": "NIB001",
                "nama": "Laptop Dell",
                ...
            },
            {
                "id": 2,
                "barcode": "BRG000000002",
                "nibar": "NIB002",
                "nama": "Printer HP",
                ...
            }
        ],
        "first_page_url": "http://localhost:8000/api/inventory?page=1",
        "from": 1,
        "last_page": 5,
        "last_page_url": "http://localhost:8000/api/inventory?page=5",
        "next_page_url": "http://localhost:8000/api/inventory?page=2",
        "path": "http://localhost:8000/api/inventory",
        "per_page": 20,
        "prev_page_url": null,
        "to": 20,
        "total": 100
    }
}
```

**Example Request:**
```bash
# Default (20 items per page)
curl -X GET http://localhost:8000/api/inventory

# Custom per page
curl -X GET http://localhost:8000/api/inventory?per_page=50

# Specific page
curl -X GET http://localhost:8000/api/inventory?page=2&per_page=20
```

---

### 4. Search Items

Mencari barang berdasarkan keyword.

**Endpoint:** `GET /inventory/search`

**Query Parameters:**
- `q` (string, required) - Keyword pencarian

**Pencarian dilakukan pada field:**
- nama
- barcode
- nibar
- kode_barang
- lokasi
- pemakai

**Response Success (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "barcode": "BRG000000001",
            "nama": "Laptop Dell",
            "lokasi": "Ruang IT",
            ...
        },
        {
            "id": 5,
            "barcode": "BRG000000005",
            "nama": "Laptop HP",
            "lokasi": "Ruang Admin",
            ...
        }
    ]
}
```

**Example Request:**
```bash
# Cari berdasarkan nama
curl -X GET "http://localhost:8000/api/inventory/search?q=laptop"

# Cari berdasarkan lokasi
curl -X GET "http://localhost:8000/api/inventory/search?q=ruang+it"

# Cari berdasarkan barcode
curl -X GET "http://localhost:8000/api/inventory/search?q=BRG000000001"
```

---

## Use Cases untuk Mobile App

### Scan Barcode & Get Data

```javascript
// Setelah scan barcode
const barcode = "BRG000000001";

fetch(`http://localhost:8000/api/inventory/barcode/${barcode}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Barang ditemukan:", data.data);
            // Tampilkan detail barang
        } else {
            console.log("Barang tidak ditemukan");
        }
    });
```

### Update Status Barang

```javascript
// Update status setelah pemeriksaan
const barcode = "BRG000000001";

fetch(`http://localhost:8000/api/inventory/barcode/${barcode}`, {
    method: 'PUT',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        status: 'Rusak Ringan',
        keterangan: 'Ditemukan kerusakan pada keyboard',
        lokasi: 'Ruang Perbaikan'
    })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log("Update berhasil:", data.data);
    }
});
```

### Search Functionality

```javascript
// Search barang
const keyword = "laptop";

fetch(`http://localhost:8000/api/inventory/search?q=${keyword}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(`Ditemukan ${data.data.length} barang`);
            data.data.forEach(item => {
                console.log(`- ${item.nama} (${item.barcode})`);
            });
        }
    });
```

---

## Error Codes

| Code | Description |
|------|-------------|
| 200  | Success |
| 404  | Resource not found |
| 422  | Validation error |
| 500  | Server error |

---

## Validation Rules

### Update Item (PUT /inventory/barcode/{barcode})

All fields are optional:

- `nibar`: string
- `kode_barang`: string
- `nama`: string
- `spesifikasi`: string
- `lokasi`: string
- `pemakai`: string
- `status`: string
- `jabatan`: string
- `identitas`: string
- `alamat`: string
- `no_bast`: string
- `tgl_bast`: date (format: YYYY-MM-DD)
- `dokumen`: string
- `no_dok`: string
- `tgl_dok`: date (format: YYYY-MM-DD)
- `keterangan`: string
- `no_simda`: string
- `no_mesin`: string
- `tahun`: string

---

## Postman Collection

Import collection untuk testing:

```json
{
    "info": {
        "name": "Inventory Management API",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "item": [
        {
            "name": "Get Item by Barcode",
            "request": {
                "method": "GET",
                "header": [],
                "url": {
                    "raw": "http://localhost:8000/api/inventory/barcode/BRG000000001",
                    "protocol": "http",
                    "host": ["localhost"],
                    "port": "8000",
                    "path": ["api", "inventory", "barcode", "BRG000000001"]
                }
            }
        },
        {
            "name": "Update Item by Barcode",
            "request": {
                "method": "PUT",
                "header": [
                    {
                        "key": "Content-Type",
                        "value": "application/json"
                    }
                ],
                "body": {
                    "mode": "raw",
                    "raw": "{\n    \"lokasi\": \"Ruang Server\",\n    \"status\": \"Baik\"\n}"
                },
                "url": {
                    "raw": "http://localhost:8000/api/inventory/barcode/BRG000000001",
                    "protocol": "http",
                    "host": ["localhost"],
                    "port": "8000",
                    "path": ["api", "inventory", "barcode", "BRG000000001"]
                }
            }
        },
        {
            "name": "Get All Items",
            "request": {
                "method": "GET",
                "header": [],
                "url": {
                    "raw": "http://localhost:8000/api/inventory?per_page=20",
                    "protocol": "http",
                    "host": ["localhost"],
                    "port": "8000",
                    "path": ["api", "inventory"],
                    "query": [
                        {
                            "key": "per_page",
                            "value": "20"
                        }
                    ]
                }
            }
        },
        {
            "name": "Search Items",
            "request": {
                "method": "GET",
                "header": [],
                "url": {
                    "raw": "http://localhost:8000/api/inventory/search?q=laptop",
                    "protocol": "http",
                    "host": ["localhost"],
                    "port": "8000",
                    "path": ["api", "inventory", "search"],
                    "query": [
                        {
                            "key": "q",
                            "value": "laptop"
                        }
                    ]
                }
            }
        }
    ]
}
```

---

## Future Enhancements

Untuk production, pertimbangkan menambahkan:

1. **Authentication**: Laravel Sanctum untuk mobile app
2. **Rate Limiting**: Batasi jumlah request per IP
3. **API Versioning**: Versioning API (v1, v2, dst)
4. **Response Caching**: Cache response untuk performa lebih baik
5. **Logging**: Log semua API requests
6. **HTTPS**: Gunakan HTTPS untuk keamanan

---

**Last Updated:** October 2024  
**Version:** 1.0
