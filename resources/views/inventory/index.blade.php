@extends('layouts.app')

@section('title', 'Data Barang')
@section('page-title', 'Data Barang Inventory')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex gap-2">
        <a href="{{ route('inventory.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>Tambah Barang
        </a>
        <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-file-csv mr-2"></i>Import CSV
        </button>
        <button onclick="printSelected()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
            <i class="fas fa-print mr-2"></i>Cetak Stiker (<span id="selectedCount">0</span>)
        </button>
        <button onclick="printAll()" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">
            <i class="fas fa-file-pdf mr-2"></i>Cetak Semua
        </button>
    </div>
    
    <div class="flex gap-2">
        <input 
            type="text" 
            id="searchInput" 
            placeholder="Cari barang..." 
            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        <button onclick="searchBarang()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
            <i class="fas fa-search"></i>
        </button>
    </div>
</div>

<!-- Data Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full" id="barangTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barcode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIBAR</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($barang as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="item-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="{{ $item->id }}" onchange="updateSelectedCount()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono">{{ $item->barcode }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->nibar }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->kode_barang }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $item->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->lokasi }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('inventory.show', $item->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('inventory.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="confirmDelete({{ $item->id }})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data barang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 bg-gray-50">
        {{ $barang->links() }}
    </div>
</div>

<!-- Import CSV Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Import Data dari CSV</h3>
        <form action="{{ route('inventory.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Pilih File CSV</label>
                <input 
                    type="file" 
                    name="csv_file" 
                    accept=".csv"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
                <p class="text-sm text-gray-500 mt-2">
                    <strong>Format CSV Header:</strong><br>
                    nibar, kode_barang, nama_barang, spesifikasi_nama_barang, lokasi, nama_pemakai, status_pemakai, jabatan, nomor_identitas_pemakai, alamat_pemakai, bast_nomor, bast_tanggal, dokumen_nama, dokumen_nomor, dokumen_tanggal, keterangan, no_simda, new, tahun, no_mesin
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    * Barcode akan di-generate otomatis<br>
                    * Format tanggal: "DD Bulan YYYY" (contoh: 29 Agustus 2024) atau YYYY-MM-DD<br>
                    * Template tersedia di: storage/app/template_import.csv
                </p>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Import
                </button>
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Konfirmasi Hapus</h3>
        <p class="mb-6">Apakah Anda yakin ingin menghapus barang ini?</p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    Hapus
                </button>
                <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDelete(id) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = `/inventory/${id}`;
        modal.classList.remove('hidden');
    }

    function searchBarang() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const table = document.getElementById('barangTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const text = row.textContent.toLowerCase();
            
            if (text.includes(input)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    // Search on enter key
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchBarang();
        }
    });

    // Toggle select all checkboxes
    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const checkboxes = document.getElementsByClassName('item-checkbox');
        
        for (let i = 0; i < checkboxes.length; i++) {
            const row = checkboxes[i].closest('tr');
            // Only select visible rows
            if (row.style.display !== 'none') {
                checkboxes[i].checked = selectAllCheckbox.checked;
            }
        }
        
        updateSelectedCount();
    }

    // Update selected count display
    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.item-checkbox:checked');
        const count = checkboxes.length;
        document.getElementById('selectedCount').textContent = count;
        
        // Update "Select All" checkbox state
        const allCheckboxes = document.querySelectorAll('.item-checkbox');
        const visibleCheckboxes = Array.from(allCheckboxes).filter(cb => cb.closest('tr').style.display !== 'none');
        const checkedVisibleCheckboxes = visibleCheckboxes.filter(cb => cb.checked);
        
        const selectAllCheckbox = document.getElementById('selectAll');
        selectAllCheckbox.checked = visibleCheckboxes.length > 0 && visibleCheckboxes.length === checkedVisibleCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedVisibleCheckboxes.length > 0 && checkedVisibleCheckboxes.length < visibleCheckboxes.length;
    }

    // Print selected items
    function printSelected() {
        const checkboxes = document.querySelectorAll('.item-checkbox:checked');
        
        if (checkboxes.length === 0) {
            alert('Pilih minimal 1 item untuk dicetak!');
            return;
        }
        
        const ids = Array.from(checkboxes).map(cb => cb.value);
        
        // Open PDF in new tab with selected IDs
        const url = '{{ route("inventory.pdf") }}?ids=' + ids.join(',');
        window.open(url, '_blank');
    }

    // Print all items
    function printAll() {
        const url = '{{ route("inventory.pdf") }}';
        window.open(url, '_blank');
    }
</script>
@endsection
