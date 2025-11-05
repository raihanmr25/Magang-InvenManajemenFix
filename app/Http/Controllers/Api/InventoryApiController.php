<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangPemakaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // <-- TAMBAHKAN INI

class InventoryApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = BarangPemakaian::query();

            // Handle pagination if "page" parameter is present
            if ($request->has('page')) {
                $items = $query->paginate(15); // 15 items per page
            } else {
                $items = $query->get();
            }

            return response()->json([
                'success' => true,
                'data' => $items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get item by barcode.
     */
    public function getByBarcode($barcode)
    {
        try {
            $item = BarangPemakaian::where('barcode', $barcode)->first();

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barang dengan barcode ' . $barcode . ' tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $item
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update item by barcode.
     */
    public function updateByBarcode(Request $request, $barcode)
    {
        try {
            $item = BarangPemakaian::where('barcode', $barcode)->first();

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barang dengan barcode ' . $barcode . ' tidak ditemukan.'
                ], 404);
            }

            // Validasi (opsional namun direkomendasikan)
            $validator = Validator::make($request->all(), [
                'nama' => 'sometimes|string|max:255',
                'lokasi' => 'sometimes|string|max:255',
                'pemakai' => 'sometimes|string|max:255',
                // Tambahkan validasi lain jika perlu
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update item dengan semua data dari request
            // Model Anda sudah memiliki $fillable, jadi ini aman
            $item->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil diupdate',
                'data' => $item
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search items.
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('q');

            if (empty($query)) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            $results = BarangPemakaian::where('nama', 'LIKE', "%{$query}%")
                ->orWhere('barcode', 'LIKE', "%{$query}%")
                ->orWhere('kode_barang', 'LIKE', "%{$query}%")
                ->orWhere('lokasi', 'LIKE', "%{$query}%")
                ->orWhere('pemakai', 'LIKE', "%{$query}%")
                ->get();

            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencari: ' . $e->getMessage()
            ], 500);
        }
    }

    // --- FUNGSI BARU (CREATE) ---
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi data yang masuk
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'barcode' => 'required|string|max:255|unique:barang_pemakaian',
                'kode_barang' => 'required|string|max:255',
                'lokasi' => 'nullable|string',
                'pemakai' => 'nullable|string',
                'status' => 'nullable|string',
                // tambahkan validasi lain sesuai kebutuhan
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal. Pastikan Barcode unik dan Nama/Kode Barang terisi.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Buat item baru
            // Pastikan model BarangPemakaian punya $fillable untuk semua kolom ini
            $item = BarangPemakaian::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Barang baru berhasil ditambahkan!',
                'data' => $item
            ], 201); // 201 = Created

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan barang: ' . $e->getMessage()
            ], 500);
        }
    }

    // --- FUNGSI BARU (DELETE) ---
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($barcode)
    {
        try {
            $item = BarangPemakaian::where('barcode', $barcode)->first();

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barang dengan barcode ' . $barcode . ' tidak ditemukan.'
                ], 404);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }
}