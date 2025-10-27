@extends('layouts.app')

@section('title', 'Detail Barang')
@section('page-title', 'Detail Barang')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6 flex justify-between items-center">
        <h3 class="text-xl font-bold">Informasi Barang</h3>
        <div class="flex gap-2">
            <a href="{{ route('inventory.edit', $barangPemakaian->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('inventory.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Barcode Display -->
    <div class="mb-6 text-center bg-gray-50 p-6 rounded-lg border-2 border-dashed border-gray-300">
        <h4 class="text-sm font-medium text-gray-700 mb-3">
            <i class="fas fa-qrcode mr-2"></i>Scan Barcode untuk Akses Langsung
        </h4>
        @if(Storage::exists('barcode/' . $barangPemakaian->barcode . '.png'))
            <div class="bg-white inline-block p-4 rounded-lg shadow-sm mb-3">
                <img src="{{ asset('storage/barcode/' . $barangPemakaian->barcode . '.png') }}" alt="Barcode" class="mx-auto" style="height: 100px;">
            </div>
        @endif

        <p class="text-xl font-mono font-bold mb-2">{{ $barangPemakaian->barcode }}</p>
        
        <p class="text-xs text-gray-500 bg-gray-100 inline-block px-3 py-1 rounded">
            <i class="fas fa-link mr-1"></i>URL: {{ url('/inventory/scan/' . $barangPemakaian->barcode) }}
        </p>
        <p class="text-xs text-blue-600 mt-2">
            <i class="fas fa-info-circle mr-1"></i>Barcode berisi link ke halaman ini
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Column 1 -->
        <div>
            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">NIBAR</label>
                <p class="text-gray-900">{{ $barangPemakaian->nibar ?: '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Kode Barang</label>
                <p class="text-gray-900">{{ $barangPemakaian->kode_barang ?: '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Nama Barang</label>
                <p class="text-gray-900 font-semibold">{{ $barangPemakaian->nama }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Spesifikasi</label>
                <p class="text-gray-900">{{ $barangPemakaian->spesifikasi ?: '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Lokasi</label>
                <p class="text-gray-900">{{ $barangPemakaian->lokasi ?: '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Pemakai</label>
                <p class="text-gray-900">{{ $barangPemakaian->pemakai ?: '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Status</label>
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    {{ $barangPemakaian->status ?: 'Tidak Ada' }}
                </span>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Jabatan</label>
                <p class="text-gray-900">{{ $barangPemakaian->jabatan ?: '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Identitas</label>
                <p class="text-gray-900">{{ $barangPemakaian->identitas ?: '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Alamat</label>
                <p class="text-gray-900">{{ $barangPemakaian->alamat ?: '-' }}</p>
            </div>
        </div>

        <!-- Column 2 -->
        <div>
            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">No. BAST</label>
                <p class="text-gray-900">{{ $barangPemakaian->no_bast ?: '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Tgl. BAST</label>
                <p class="text-gray-900">{{ $barangPemakaian->tgl_bast ? $barangPemakaian->tgl_bast->format('d/m/Y') : '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Dokumen</label>
                <p class="text-gray-900">{{ $barangPemakaian->dokumen ?: '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">No. Dokumen</label>
                <p class="text-gray-900">{{ $barangPemakaian->no_dok ?: '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Tgl. Dokumen</label>
                <p class="text-gray-900">{{ $barangPemakaian->tgl_dok ? $barangPemakaian->tgl_dok->format('d/m/Y') : '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Keterangan</label>
                <p class="text-gray-900">{{ $barangPemakaian->keterangan ?: '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">No. SIMDA</label>
                <p class="text-gray-900">{{ $barangPemakaian->no_simda ?: '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">No. Mesin</label>
                <p class="text-gray-900">{{ $barangPemakaian->no_mesin ?: '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Tahun</label>
                <p class="text-gray-900">{{ $barangPemakaian->tahun ?: '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Dibuat Pada</label>
                <p class="text-gray-900">{{ $barangPemakaian->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-medium mb-1">Terakhir Diupdate</label>
                <p class="text-gray-900">{{ $barangPemakaian->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
