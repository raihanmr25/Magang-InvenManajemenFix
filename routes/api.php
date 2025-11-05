<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InventoryApiController;

// Rute API untuk Manajemen Inventaris
Route::middleware('api')->group(function () {
    
    // GET (Semua Barang) - (Sudah ada)
    Route::get('/inventory', [InventoryApiController::class, 'index']);
    
    // GET (Cari Barang) - (Sudah ada)
    Route::get('/inventory/search', [InventoryApiController::class, 'search']);
    
    // GET (Satu Barang by Barcode) - (Sudah ada)
    Route::get('/inventory/barcode/{barcode}', [InventoryApiController::class, 'getByBarcode']);
    
    // PUT (Update Barang by Barcode) - (Sudah ada)
    Route::put('/inventory/barcode/{barcode}', [InventoryApiController::class, 'updateByBarcode']);
    
    // --- TAMBAHAN BARU ---

    // POST (Buat Barang Baru)
    Route::post('/inventory', [InventoryApiController::class, 'store']);
    
    // DELETE (Hapus Barang by Barcode)
    Route::delete('/inventory/barcode/{barcode}', [InventoryApiController::class, 'destroy']);
    
    // --- AKHIR TAMBAHAN ---
});