<?php

namespace App\Http\Controllers;

use App\Models\BarangPemakaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\DNS1D;
use TCPDF;

class BarangPemakaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = BarangPemakaian::latest()->paginate(20);
        return view('inventory.index', compact('barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nibar' => 'nullable|string',
            'kode_barang' => 'nullable|string',
            'nama' => 'required|string',
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

        // Generate unique barcode
        $barcode = $this->generateUniqueBarcode();
        $validated['barcode'] = $barcode;

        $barang = BarangPemakaian::create($validated);

        // Generate barcode image
        $this->generateBarcodeImage($barcode);

        return redirect()->route('inventory.index')->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangPemakaian $inventory)
    {
        return view('inventory.show', ['barangPemakaian' => $inventory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangPemakaian $inventory)
    {
        return view('inventory.edit', ['barangPemakaian' => $inventory]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangPemakaian $inventory)
    {
        $validated = $request->validate([
            'nibar' => 'nullable|string',
            'kode_barang' => 'nullable|string',
            'nama' => 'required|string',
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

        $inventory->update($validated);

        return redirect()->route('inventory.index')->with('success', 'Barang berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangPemakaian $inventory)
    {
        // Delete barcode image
        Storage::delete('public/barcode/' . $inventory->barcode . '.png');
        
        $inventory->delete();

        return redirect()->route('inventory.index')->with('success', 'Barang berhasil dihapus');
    }

    /**
     * Generate unique barcode
     */
    public function generateUniqueBarcode()
    {
        do {
            $barcode = 'BRG' . str_pad(rand(1, 999999999), 9, '0', STR_PAD_LEFT);
        } while (BarangPemakaian::where('barcode', $barcode)->exists());

        return $barcode;
    }

    /**
     * Generate barcode image
     */
    public function generateBarcodeImage($barcode)
    {
        $generator = new DNS1D();
        $barcodeImage = $generator->getBarcodePNG($barcode, 'C128', 3, 80);
        
        // Sanitize barcode to avoid invalid file names
        $safeBarcode = preg_replace('/[^A-Za-z0-9_\-]/', '_', $barcode);

        // Decode base64 and save to storage
        $imageData = base64_decode($barcodeImage);
        Storage::put("barcode/{$safeBarcode}.png", $imageData);
    }

    
    /**
     * Import CSV
     */
    public function importCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data);

        $imported = 0;
        $errors = [];

        // Mapping dari CSV columns ke database columns
        $columnMapping = [
            'nibar' => 'nibar',
            'kode_barang' => 'kode_barang',
            'nama_barang' => 'nama',
            'spesifikasi_nama_barang' => 'spesifikasi',
            'lokasi' => 'lokasi',
            'nama_pemakai' => 'pemakai',
            'status_pemakai' => 'status',
            'jabatan' => 'jabatan',
            'nomor_identitas_pemakai' => 'identitas',
            'alamat_pemakai' => 'alamat',
            'bast_nomor' => 'no_bast',
            'bast_tanggal' => 'tgl_bast',
            'dokumen_nama' => 'dokumen',
            'dokumen_nomor' => 'no_dok',
            'dokumen_tanggal' => 'tgl_dok',
            'keterangan' => 'keterangan',
            'no_simda' => 'no_simda',
            'new' => 'new',
            'tahun' => 'tahun',
            'no_mesin' => 'no_mesin',
        ];

        foreach ($data as $index => $row) {
            try {
                if (count($header) !== count($row)) {
                    $errors[] = "Baris " . ($index + 2) . ": Jumlah kolom tidak sesuai";
                    continue;
                }

                $rowData = array_combine($header, $row);
                $mappedData = [];

                foreach ($columnMapping as $csvColumn => $dbColumn) {
                    if (isset($rowData[$csvColumn])) {
                        $value = trim($rowData[$csvColumn]);

                        // Handle empty values
                        if ($value === '' || $value === 'N/A' || strtolower($value) === 'n/a') {
                            $value = null;
                        }

                        // Handle scientific notation
                        if (is_numeric($value) && stripos($value, 'e') !== false) {
                            $value = $this->convertScientificToString($value);
                        }

                        // Format nibar if applicable
                        if ($csvColumn === 'nibar' && $value) {
                            $value = $this->formatNibar($value);
                        }

                        // Parse dates in Indonesian format
                        if (in_array($dbColumn, ['tgl_bast', 'tgl_dok']) && $value) {
                            try {
                                $months = [
                                    'Januari' => '01', 'Februari' => '02', 'Maret' => '03',
                                    'April' => '04', 'Mei' => '05', 'Juni' => '06',
                                    'Juli' => '07', 'Agustus' => '08', 'September' => '09',
                                    'Oktober' => '10', 'November' => '11', 'Desember' => '12'
                                ];

                                foreach ($months as $indonesian => $numeric) {
                                    if (stripos($value, $indonesian) !== false) {
                                        $parts = preg_split('/\s+/', $value);
                                        if (count($parts) >= 3) {
                                            $day = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                                            $month = $numeric;
                                            $year = $parts[2];
                                            $value = "$year-$month-$day";
                                        }
                                        break;
                                    }
                                }
                            } catch (\Exception $e) {
                                $value = null;
                            }
                        }

                        $mappedData[$dbColumn] = $value;
                    }
                }

                // Generate barcode
                $mappedData['barcode'] = $this->generateUniqueBarcode();

                $barang = BarangPemakaian::create($mappedData);
                $this->generateBarcodeImage($barang->barcode);

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        $message = "$imported data berhasil diimport";
        if (count($errors) > 0) {
            $message .= ". " . count($errors) . " data gagal diimport.";
        }

        return redirect()->route('inventory.index')->with('success', $message);
    }

    /**
     * Convert scientific notation to full numeric string.
     */
    private function convertScientificToString($value)
    {
        if (!is_numeric($value)) return $value;

        $value = strtolower($value);
        if (strpos($value, 'e') === false) return $value;

        $parts = explode('e', $value);
        $base = $parts[0];
        $exponent = (int) $parts[1];

        // Remove decimal and count digits
        if (strpos($base, '.') !== false) {
            $decimals = strlen(substr(strrchr($base, "."), 1));
            $base = str_replace('.', '', $base);
            $exponent -= $decimals;
        }

        if ($exponent >= 0) {
            return $base . str_repeat('0', $exponent);
        } else {
            return '0.' . str_repeat('0', abs($exponent) - 1) . $base;
        }
    }

    /**
     * Format NIBAR: assumes fixed structure like 10.020.010.012.000.600
     */
    private function formatNibar($value)
    {
        // Remove non-digit characters
        $digits = preg_replace('/\D/', '', $value);

        // Pad to 18 digits
        $digits = str_pad($digits, 18, '0', STR_PAD_LEFT);

        // Break into 6 parts: [2,3,3,3,3,3]
        $parts = [
            substr($digits, 0, 2),
            substr($digits, 2, 3),
            substr($digits, 5, 3),
            substr($digits, 8, 3),
            substr($digits, 11, 3),
            substr($digits, 14, 3),
        ];

        return implode('.', $parts);
    }


    /**
     * Generate PDF for printing stickers
     */
    public function generatePDF(Request $request)
    {
        // Check if specific IDs are provided
        $ids = $request->get('ids');
        
        if ($ids) {
            // Convert comma-separated string to array
            $idsArray = is_array($ids) ? $ids : explode(',', $ids);
            $data = BarangPemakaian::whereIn('id', $idsArray)->get();
        } else {
            // Get all items if no IDs specified
            $data = BarangPemakaian::all();
        }

        if ($data->isEmpty()) {
            return response()->json(['error' => 'Data Kosong']);
        }

        $pdf = new TCPDF();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetFont('helvetica', '', 13);
        $pdf->setMargins(4, 4, 4);
        $pdf->AddPage('P', array(210, 330)); // Ukuran Kertas F4

        // $logo = public_path('assets/Lambang_Kota_Semarang.png');
        
        // Jumlah item per halaman
        $counter = 0;
        $itemsPerPage = 14;

        $html = '';
        foreach ($data as $item) {
            // $barcodepath = storage_path('public/barcode/' . $item->barcode . '.png');
            // $barcodepath = storage_path('app/public/barcode/' . $item->barcode . '.png');
            $barcodePath = str_replace('\\', '/', storage_path("app/public/barcode/{$item->barcode}.png"));
            $logoPath = str_replace('\\', '/', storage_path("app/public/logo.png"));



            $html .= '
                <table style="border-collapse: collapse; border-bottom: 0.1px dashed gray; border-top: 0.1px dashed gray; padding: 6px 6px 0px 2px;">
                    <tr>
                        <td style="border-bottom:none; border-left: 0.1px dashed gray; border-right: 0.1px dashed gray;">
                            <table style="border-collapse: collapse;">
                                <tr>
                                    <td style="border: 1px solid black; text-align: center; width:15%;" rowspan="2" >
                                        <img src="' . $logoPath . '" alt="Logo" width="25px" top="2px">
                                    </td>
                                    <td style="border: 1px solid black; width: 85%; text-align: center;"><strong>' . $item->nibar . '</strong></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; width: 85%; text-align: center;"><strong>' . $item->kode_barang . '</strong></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; height: 8px; text-align: center; font-size:10px;" colspan="3"><strong>PEMERINTAH KOTA SEMARANG</strong></td>
                                </tr>
                            </table>
                        </td>
                        <td style="border-bottom:none; border-right: 0.1px dashed gray;">
                            <table>
                                <tr>
                                    <td>
                                        <img src="' . $barcodePath . '" style="height: 75px; width: 400px;" alt="Barcode">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            ';

            // Counter item untuk membuat new page
            $counter++;
            if ($counter % $itemsPerPage == 0) {
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdf->AddPage('P', array(210, 330));
                $html = ''; 
            }
        }

        if (!empty($html)) {
            $pdf->writeHTML($html, true, false, true, false, '');
        }

        return $pdf->Output('AssetCard.pdf', 'I');
    }

    /**
     * Dashboard
     */
    public function dashboard()
    {
        $totalBarang = BarangPemakaian::count();
        $barangPerStatus = BarangPemakaian::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        $barangPerLokasi = BarangPemakaian::selectRaw('lokasi, COUNT(*) as count')
            ->groupBy('lokasi')
            ->limit(10)
            ->get();
        
        $recentBarang = BarangPemakaian::latest()->limit(5)->get();

        return view('dashboard', compact('totalBarang', 'barangPerStatus', 'barangPerLokasi', 'recentBarang'));
    }

    /**
     * Handle barcode scan and redirect to item details
     */
    public function scanBarcode($barcode)
    {
        $barang = BarangPemakaian::where('barcode', $barcode)->first();
        
        if (!$barang) {
            return redirect()->route('inventory.index')
                ->with('error', 'Barcode tidak ditemukan');
        }
        
        return redirect()->route('inventory.show', $barang->id);
    }
}
