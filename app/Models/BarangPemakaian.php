<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangPemakaian extends Model
{
    use HasFactory;

    protected $table = 'barang_pemakaian';

    protected $fillable = [
        'barcode',
        'nibar',
        'kode_barang',
        'nama',
        'spesifikasi',
        'lokasi',
        'pemakai',
        'status',
        'jabatan',
        'identitas',
        'alamat',
        'no_bast',
        'tgl_bast',
        'dokumen',
        'no_dok',
        'tgl_dok',
        'keterangan',
        'no_simda',
        'no_mesin',
        'tahun',
    ];

    protected $casts = [
        'tgl_bast' => 'date',
        'tgl_dok' => 'date',
    ];
}
