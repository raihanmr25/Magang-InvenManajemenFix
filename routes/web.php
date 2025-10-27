<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangPemakaianController;
use App\Http\Controllers\Auth\LoginController;

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [BarangPemakaianController::class, 'dashboard'])->name('dashboard');
    
    // Inventory Management
    Route::resource('inventory', BarangPemakaianController::class)->names('inventory');
    
    // CSV Import
    Route::post('/inventory/import', [BarangPemakaianController::class, 'importCSV'])->name('inventory.import');
    
    // Generate PDF
    Route::get('/inventory/pdf/generate', [BarangPemakaianController::class, 'generatePDF'])->name('inventory.pdf');
    
    // Barcode Scan Route
    Route::get('/inventory/scan/{barcode}', [BarangPemakaianController::class, 'scanBarcode'])->name('inventory.scan');
});
