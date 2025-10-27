@extends('layouts.app')

@section('title', 'Edit Barang')
@section('page-title', 'Edit Barang')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('inventory.update', $barangPemakaian->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Column 1 -->
            <div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Barcode</label>
                    <input type="text" value="{{ $barangPemakaian->barcode }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                    <p class="text-sm text-gray-500 mt-1">Barcode tidak dapat diubah</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">NIBAR</label>
                    <input type="text" name="nibar" value="{{ old('nibar', $barangPemakaian->nibar) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Kode Barang</label>
                    <input type="text" name="kode_barang" value="{{ old('kode_barang', $barangPemakaian->kode_barang) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $barangPemakaian->nama) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Spesifikasi</label>
                    <textarea name="spesifikasi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('spesifikasi', $barangPemakaian->spesifikasi) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $barangPemakaian->lokasi) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Pemakai</label>
                    <input type="text" name="pemakai" value="{{ old('pemakai', $barangPemakaian->pemakai) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Status</option>
                        <option value="Baik" {{ old('status', $barangPemakaian->status) == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('status', $barangPemakaian->status) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('status', $barangPemakaian->status) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        <option value="Hilang" {{ old('status', $barangPemakaian->status) == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $barangPemakaian->jabatan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Identitas</label>
                    <input type="text" name="identitas" value="{{ old('identitas', $barangPemakaian->identitas) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Alamat</label>
                    <textarea name="alamat" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('alamat', $barangPemakaian->alamat) }}</textarea>
                </div>
            </div>

            <!-- Column 2 -->
            <div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">No. BAST</label>
                    <input type="text" name="no_bast" value="{{ old('no_bast', $barangPemakaian->no_bast) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Tgl. BAST</label>
                    <input type="date" name="tgl_bast" value="{{ old('tgl_bast', $barangPemakaian->tgl_bast?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Dokumen</label>
                    <input type="text" name="dokumen" value="{{ old('dokumen', $barangPemakaian->dokumen) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">No. Dokumen</label>
                    <input type="text" name="no_dok" value="{{ old('no_dok', $barangPemakaian->no_dok) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Tgl. Dokumen</label>
                    <input type="date" name="tgl_dok" value="{{ old('tgl_dok', $barangPemakaian->tgl_dok?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('keterangan', $barangPemakaian->keterangan) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">No. SIMDA</label>
                    <input type="text" name="no_simda" value="{{ old('no_simda', $barangPemakaian->no_simda) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">No. Mesin</label>
                    <input type="text" name="no_mesin" value="{{ old('no_mesin', $barangPemakaian->no_mesin) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Tahun</label>
                    <input type="text" name="tahun" value="{{ old('tahun', $barangPemakaian->tahun) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="mt-6 flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save mr-2"></i>Update
            </button>
            <a href="{{ route('inventory.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
        </div>
    </form>
</div>
@endsection
