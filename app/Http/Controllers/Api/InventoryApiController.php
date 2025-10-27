<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangPemakaian;
use Illuminate\Http\Request;

class InventoryApiController extends Controller
{
    /**
     * Get item by barcode (for mobile scanning)
     */
    public function getByBarcode($barcode)
    {
        $barang = BarangPemakaian::where('barcode', $barcode)->first();

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $barang
        ]);
    }

    /**
     * Update item by barcode
     */
    public function updateByBarcode(Request $request, $barcode)
    {
        $barang = BarangPemakaian::where('barcode', $barcode)->first();

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nibar' => 'nullable|string',
            'kode_barang' => 'nullable|string',
            'nama' => 'nullable|string',
            'spesifikasi' => 'nullable|string',
            'lokasi' => 'nullable|string',
            'pemakai' => 'nullable|string',
            'status' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'identitas' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_bast' => 'nullable|string',
            'tgl_bast' => 'nullable|date',
            'dokumen' => 'nullable|string',
            'no_dok' => 'nullable|string',
            'tgl_dok' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'no_simda' => 'nullable|string',
            'no_mesin' => 'nullable|string',
            'tahun' => 'nullable|string',
        ]);

        $barang->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diupdate',
            'data' => $barang
        ]);
    }

    /**
     * Get all items (with pagination)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $barang = BarangPemakaian::latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $barang
        ]);
    }

    /**
     * Search items
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $barang = BarangPemakaian::where('nama', 'like', "%$query%")
            ->orWhere('barcode', 'like', "%$query%")
            ->orWhere('nibar', 'like', "%$query%")
            ->orWhere('kode_barang', 'like', "%$query%")
            ->orWhere('lokasi', 'like', "%$query%")
            ->orWhere('pemakai', 'like', "%$query%")
            ->get();

        return response()->json([
            'success' => true,
            'data' => $barang
        ]);
    }
}
