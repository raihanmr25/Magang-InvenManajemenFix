<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangPemakaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // <-- 1. TAMBAHKAN IMPORT DB

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

            $validator = Validator::make($request->all(), [
                'nama' => 'sometimes|string|max:255',
                'lokasi' => 'sometimes|string|max:255',
                'pemakai' => 'sometimes|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // 1. Ubah validasi barcode menjadi nullable (opsional)
            // Hapus 'required' pada barcode
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'kode_barang' => 'required|string|max:255',
                'lokasi' => 'nullable|string',
                'pemakai' => 'nullable|string',
                'status' => 'nullable|string',
                // Barcode tidak wajib dikirim dari HP
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // 2. Logika Auto-Generate Barcode (BRG + 9 digit angka)
            // Ambil barang terakhir untuk tahu urutan ID selanjutnya
            $lastItem = BarangPemakaian::orderBy('id', 'desc')->first();
            $nextId = $lastItem ? $lastItem->id + 1 : 1;
            
            // Format: BRG000000001
            $generatedBarcode = 'BRG' . str_pad($nextId, 9, '0', STR_PAD_LEFT);

            // Gabungkan data request dengan barcode baru
            $data = $request->all();
            $data['barcode'] = $generatedBarcode;

            // 3. Simpan ke Database
            $item = BarangPemakaian::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Barang baru berhasil ditambahkan!',
                'data' => $item
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan barang: ' . $e->getMessage()
            ], 500);
        }
    }

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

    // --- 2. FUNGSI STATISTIK BARU ---
    /**
     * Get inventory statistics for dashboard.
     */
    public function getStats()
    {
        try {
            $totalBarang = BarangPemakaian::count();
            
            // Hitung barang berdasarkan status
            $byStatus = BarangPemakaian::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status'); // Hasilnya: ["Baik" => 10, "Rusak Ringan" => 2]

            // Hitung barang berdasarkan lokasi (Top 5)
            $byLokasi = BarangPemakaian::select('lokasi', DB::raw('count(*) as total'))
                ->groupBy('lokasi')
                ->orderBy('total', 'desc')
                ->take(5)
                ->pluck('total', 'lokasi');

            return response()->json([
                'success' => true,
                'data' => [
                    'total_barang' => $totalBarang,
                    'by_status' => $byStatus,
                    'by_lokasi' => $byLokasi
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }
}