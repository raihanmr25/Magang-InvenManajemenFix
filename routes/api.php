<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InventoryApiController;

Route::middleware('api')->group(function () {
    // Get item by barcode
    Route::get('/inventory/barcode/{barcode}', [InventoryApiController::class, 'getByBarcode']);
    
    // Update item by barcode
    Route::put('/inventory/barcode/{barcode}', [InventoryApiController::class, 'updateByBarcode']);
    
    // Get all items
    Route::get('/inventory', [InventoryApiController::class, 'index']);
    
    // Search items
    Route::get('/inventory/search', [InventoryApiController::class, 'search']);
});
