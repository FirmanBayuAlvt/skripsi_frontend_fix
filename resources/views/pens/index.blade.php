@extends('layouts.app')

@section('title', 'Manajemen Kandang')
@section('header-title', 'Manajemen Kandang')

@section('page-header')
<div class="flex flex-wrap justify-between items-center gap-3">
    <div class="flex items-center gap-2">
        <div class="h-8 w-1 bg-gradient-to-b from-emerald-300 to-teal-500 rounded-full"></div>
        <p class="text-emerald-200 font-medium tracking-wide">DATA KANDANG</p>
    </div>
    <div class="flex gap-2">
        <button onclick="openAddPenModal()" class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-4 py-2 rounded-xl flex items-center shadow-md transition-all transform hover:scale-[1.02]">
            <i class="fas fa-plus mr-2"></i> Tambah Kandang
        </button>
        <button onclick="openImportPenModal()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-xl flex items-center shadow-md transition-all transform hover:scale-[1.02]">
            <i class="fas fa-file-excel mr-2"></i> Impor Excel
        </button>
        <a href="{{ route('dashboard') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Stat Cards Premium -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-300 text-sm">Total Kandang</p>
                    <p class="stat-value" id="total-pens">-</p>
                </div>
                <div class="bg-emerald-500/20 p-3 rounded-2xl">
                    <i class="fas fa-warehouse text-emerald-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-300 text-sm">Kapasitas Total</p>
                    <p class="stat-value" id="total-capacity">-</p>
                </div>
                <div class="bg-blue-500/20 p-3 rounded-2xl">
                    <i class="fas fa-cow text-blue-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-300 text-sm">Jumlah Ternak Keseluruhan</p>
                    <p class="stat-value" id="total-livestock-overall">-</p>
                </div>
                <div class="bg-purple-500/20 p-3 rounded-2xl">
                    <i class="fas fa-paw text-purple-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-300 text-sm">Kandang Tersedia</p>
                    <p class="stat-value" id="available-pens">-</p>
                </div>
                <div class="bg-amber-500/20 p-3 rounded-2xl">
                    <i class="fas fa-check-circle text-amber-300 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Kandang (Tabel Utama) -->
    <div class="glass-card p-0 overflow-hidden">
        <div class="flex items-center gap-2 border-b border-white/10 p-5">
            <i class="fas fa-table-list text-emerald-400 text-xl"></i>
            <h3 class="font-bold text-white text-lg">Daftar Kandang</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="custom-table w-full text-sm text-white">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr class="text-gray-300 text-xs uppercase">
                        <th class="px-6 py-3 text-left">Kandang</th>
                        <th class="px-6 py-3 text-left">ABK</th>
                        <th class="px-6 py-3 text-left">Kategori</th>
                        <th class="px-6 py-3 text-left">Umur (Days)</th>
                        <th class="px-6 py-3 text-left">Kapasitas</th>
                        <th class="px-6 py-3 text-left">Jumlah Ternak</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pens-table-body">
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-400">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- List Tag dan BB Ternak Per Kandang -->
    <div class="glass-card p-5">
        <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
            <i class="fas fa-tags text-emerald-400"></i>
            <h3 class="font-bold text-white text-lg">List Tag dan BB Ternak Per Kandang</h3>
        </div>
        <div class="mb-4 flex flex-wrap items-center gap-4">
            <label class="text-gray-300 font-medium">Pilih Kandang:</label>
            <select id="pen-filter-livestock" class="bg-white/10 border border-white/20 rounded-xl px-3 py-2 text-white focus:ring-2 focus:ring-emerald-500">
                <option value="">-- Pilih Kandang --</option>
            </select>
            <button onclick="loadLivestockByPen()" class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-4 py-2 rounded-xl shadow-md transition">Tampilkan</button>
        </div>
        <div class="overflow-x-auto">
            <table class="custom-table w-full text-sm text-white">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr class="text-gray-300 text-xs uppercase">
                        <th class="px-3 py-2">Tagging</th>
                        <th class="px-3 py-2">Jenis Kelamin</th>
                        <th class="px-3 py-2">Jenis Domba</th>
                        <th class="px-3 py-2">BB Terbaru</th>
                        <th class="px-3 py-2">Kondisi</th>
                        <th class="px-3 py-2">Anak Kandang</th>
                        <th class="px-3 py-2">Umur (hari)</th>
                    </tr>
                </thead>
                <tbody id="livestock-table-body">
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-400">Pilih kandang terlebih dahulu</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- List Jumlah Ternak dan Total Bobot Per Kandang -->
    <div class="glass-card p-5">
        <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
            <i class="fas fa-chart-simple text-emerald-400"></i>
            <h3 class="font-bold text-white text-lg">List Jumlah Ternak dan Total Bobot Per Kandang</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="custom-table w-full text-sm text-white">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr class="text-gray-300 text-xs uppercase">
                        <th class="px-3 py-2">Kandang</th>
                        <th class="px-3 py-2">Anak Kandang (ABK)</th>
                        <th class="px-3 py-2">Kategori Kandang</th>
                        <th class="px-3 py-2">Jumlah Ternak</th>
                        <th class="px-3 py-2">Total BB (kg)</th>
                        <th class="px-3 py-2">Kapasitas</th>
                        <th class="px-3 py-2">Okupansi</th>
                    </tr>
                </thead>
                <tbody id="pen-summary-table-body">
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-400">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Kandang -->
<div id="add-pen-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div class="bg-black/40 backdrop-blur-md rounded-2xl p-6 max-w-md w-full z-10 border border-white/20 shadow-2xl">
            <h3 class="text-white text-xl font-bold mb-5">Tambah Kandang Baru</h3>
            <form id="add-pen-form" class="space-y-4 no-global-loading">
                @csrf
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Nama Kandang</label><input type="text" name="name" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Kode (opsional)</label><input type="text" name="code" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Kategori Kandang</label>
                    <select name="category" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white">
                        <option value="">Pilih Kategori</option>
                        <option value="Melahirkan">Melahirkan</option>
                        <option value="Menyusui">Menyusui</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Karantina">Karantina</option>
                        <option value="Persiapan Breeding">Persiapan Breeding</option>
                        <option value="Lapak">Lapak</option>
                        <option value="Fattening">Fattening</option>
                        <option value="Prasapih">Prasapih</option>
                        <option value="Kambing">Kambing</option>
                        <option value="Kambing Jantan">Kambing Jantan</option>
                        <option value="Breeding">Breeding</option>
                    </select>
                </div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Pilih ABK</label>
                    <select name="abk" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white">
                        <option value="">Pilih ABK</option>
                        <option value="Yudianto">Yudianto</option>
                        <option value="Rio Hanif">Rio Hanif</option>
                        <option value="Fais Al Aqib">Fais Al Aqib</option>
                        <option value="Didik Suharianto">Didik Suharianto</option>
                        <option value="Herianto">Herianto</option>
                    </select>
                </div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Kapasitas (ekor)</label><input type="number" name="capacity" min="1" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Status</label>
                    <select name="status" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white">
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeAddPenModal()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl">Batal</button>
                    <button type="button" onclick="submitAddPenForm()" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kandang -->
<div id="edit-pen-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div class="bg-black/40 backdrop-blur-md rounded-2xl p-6 max-w-md w-full z-10 border border-white/20 shadow-2xl">
            <h3 class="text-white text-xl font-bold mb-5">Edit Kandang</h3>
            <form id="edit-pen-form" class="space-y-4 no-global-loading">
                @csrf
                <input type="hidden" id="edit_pen_id" name="id">
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Nama Kandang</label><input type="text" id="edit_name" name="name" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Kode</label><input type="text" id="edit_code" name="code" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Kategori</label>
                    <select id="edit_category" name="category" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white">
                        <option value="Melahirkan">Melahirkan</option>
                        <option value="Menyusui">Menyusui</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Karantina">Karantina</option>
                        <option value="Persiapan Breeding">Persiapan Breeding</option>
                        <option value="Lapak">Lapak</option>
                        <option value="Fattening">Fattening</option>
                        <option value="Prasapih">Prasapih</option>
                        <option value="Kambing">Kambing</option>
                        <option value="Kambing Jantan">Kambing Jantan</option>
                        <option value="Breeding">Breeding</option>
                    </select>
                </div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">ABK</label>
                    <select id="edit_abk" name="abk" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white">
                        <option value="">Pilih ABK</option>
                        <option value="Yudianto">Yudianto</option>
                        <option value="Rio Hanif">Rio Hanif</option>
                        <option value="Fais Al Aqib">Fais Al Aqib</option>
                        <option value="Didik Suharianto">Didik Suharianto</option>
                        <option value="Herianto">Herianto</option>
                    </select>
                </div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Kapasitas</label><input type="number" id="edit_capacity" name="capacity" min="1" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Status</label>
                    <select id="edit_status" name="status" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white">
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditPenModal()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Impor Excel -->
<div id="import-pen-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div class="bg-black/40 backdrop-blur-md rounded-2xl p-6 max-w-lg w-full z-10 border border-white/20 shadow-2xl">
            <h3 class="text-white text-xl font-bold mb-5">Impor Data Kandang dari Excel</h3>
            <form id="import-pen-form" enctype="multipart/form-data" class="no-global-loading">
                @csrf
                <div class="mb-4"><label class="block text-gray-200 text-sm font-medium mb-2">Pilih file Excel</label><input type="file" name="file" accept=".xlsx,.xls,.csv" required class="block w-full bg-white/10 border border-white/20 rounded-xl p-2 text-white"></div>
                <div class="mb-4"><p class="text-sm text-gray-400">Format kolom: <code class="text-emerald-300">nama, kode, kategori, kapasitas, status</code>. Baris pertama harus header.</p></div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeImportPenModal()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl shadow-md">Impor</button>
                </div>
            </form>
            <div id="import-pen-progress" class="hidden mt-4 text-center"><div class="loading-spinner mx-auto"></div><p class="text-gray-400 text-sm mt-2">Memproses...</p></div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="delete-pen-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-all duration-300" onclick="closeDeletePenModal()"></div>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-black/40 backdrop-blur-md rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20 opacity-0 scale-95" id="delete-pen-modal-panel">
            <div class="px-6 pt-6 pb-4 sm:px-8 sm:pt-8">
                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-500/20 rounded-full">
                    <i class="fas fa-trash-alt text-red-400 text-2xl"></i>
                </div>
                <div class="mt-4 text-center">
                    <h3 class="text-xl font-semibold text-white" id="delete-pen-title">Hapus Kandang</h3>
                    <div class="mt-3">
                        <p class="text-sm text-gray-300" id="delete-pen-message">Apakah Anda yakin ingin menghapus kandang ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-white/5 sm:flex sm:flex-row-reverse sm:px-8">
                <button type="button" id="confirm-delete-pen-btn" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-xl shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition duration-200">Hapus</button>
                <button type="button" onclick="closeDeletePenModal()" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-300 bg-white/10 border border-white/20 rounded-xl shadow-sm hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-gray-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition duration-200">Batal</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ==================== VARIABLES ====================
let currentPage = 1;
let filters = {};
let deletePenId = null;

// ==================== DOCUMENT READY ====================
document.addEventListener('DOMContentLoaded', function() {
    loadPensData();
    loadPenOptions();
    loadPenSummary();

    // Event listener untuk form edit kandang
    const editPenForm = document.getElementById('edit-pen-form');
    if (editPenForm) {
        editPenForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const id = document.getElementById('edit_pen_id').value;
            const data = {
                name: document.getElementById('edit_name').value,
                code: document.getElementById('edit_code').value,
                category: document.getElementById('edit_category').value,
                abk: document.getElementById('edit_abk').value,
                capacity: parseInt(document.getElementById('edit_capacity').value),
                status: document.getElementById('edit_status').value,
            };
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = 'Menyimpan...';
            try {
                const response = await fetch(`/web-api/pens/${id}/update`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (response.ok && result.success) {
                    TernakPark.ui.showToast('Kandang berhasil diperbarui', 'success');
                    closeEditPenModal();
                    loadPensData();
                    loadPenOptions();
                    loadPenSummary();
                } else {
                    TernakPark.ui.showToast(result.message || 'Gagal memperbarui kandang', 'error');
                }
            } catch (error) {
                console.error(error);
                TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
                if (typeof hideLoading === 'function') {
                    hideLoading();
                }
            }
        });
    }

    // Event listener untuk tombol konfirmasi hapus
    const confirmDeleteBtn = document.getElementById('confirm-delete-pen-btn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', executeDeletePen);
    }
});

// ==================== DAFTAR KANDANG (TABEL UTAMA) ====================
async function loadPensData() {
    try {
        const response = await TernakPark.api.fetchData('/web-api/pens/data');
        if (response.success) {
            renderPensTable(response.data.pens);
            updatePensStats(response.data.stats);
        } else {
            showError(response.message);
        }
    } catch (error) {
        console.error(error);
        document.getElementById('pens-table-body').innerHTML = '<tr><td colspan="8" class="text-center py-8 text-red-400">Koneksi error</td></tr>';
    } finally {
        if (typeof hideLoading === 'function') {
            hideLoading();
        }
    }
}

function renderPensTable(pens) {
    const tbody = document.getElementById('pens-table-body');
    if (!pens || pens.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-8 text-gray-400">Belum ada data</td></tr>';
        return;
    }
    let html = '';
    for (const pen of pens) {
        const occupancyPercent = pen.capacity ? (pen.current_occupancy / pen.capacity * 100) : 0;
        html += `
            <tr class="border-b border-white/10 hover:bg-white/5 transition">
                <td class="px-6 py-3 font-medium">${escapeHtml(pen.name || '-')}</td>
                <td class="px-6 py-3">${escapeHtml(pen.abk || '-')}</td>
                <td class="px-6 py-3"><span class="px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-200 text-xs">${escapeHtml(pen.category)}</span></td>
                <td class="px-6 py-3">${pen.age_days || 0} hari</td>
                <td class="px-6 py-3">${pen.capacity}</td>
                <td class="px-6 py-3">
                    ${pen.current_occupancy || 0}
                    <div class="w-24 bg-white/10 rounded-full h-1.5 mt-1">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: ${occupancyPercent}%"></div>
                    </div>
                </td>
                <td class="px-6 py-3"><span class="px-2 py-1 rounded-full text-xs ${pen.status === 'active' ? 'bg-emerald-500/20 text-emerald-200' : 'bg-red-500/20 text-red-200'}">${pen.status === 'active' ? 'Aktif' : 'Nonaktif'}</span></td>
                <td class="px-6 py-3 text-right">
                    <button onclick="openEditPen(${pen.id})" class="text-amber-400 hover:text-amber-300 mr-2" title="Edit"><i class="fas fa-edit"></i></button>
                    <button onclick="confirmDeletePen(${pen.id}, '${escapeHtml(pen.name)}')" class="text-red-400 hover:text-red-300 mr-2" title="Hapus"><i class="fas fa-trash"></i></button>
                    <a href="/pens/${pen.id}" class="text-emerald-400 hover:text-emerald-300 mr-2" title="Detail"><i class="fas fa-eye"></i></a>
                    <a href="/pens/${pen.id}/analytics" class="text-blue-400 hover:text-blue-300" title="Analisis"><i class="fas fa-chart-line"></i></a>
                </td>
            </tr>
        `;
    }
    tbody.innerHTML = html;
}

function updatePensStats(stats) {
    if (!stats) return;
    document.getElementById('total-pens').innerText = stats.total_pens || 0;
    document.getElementById('total-capacity').innerText = stats.total_capacity || 0;
    document.getElementById('total-livestock-overall').innerText = stats.total_livestock_overall || 0;
    document.getElementById('available-pens').innerText = stats.available_pens || 0;
}

// ==================== LIST TAG & BB TERNAK PER KANDANG ====================
async function loadPenOptions() {
    try {
        const response = await TernakPark.api.fetchData('/web-api/pens/data');
        if (response.success && response.data.pens) {
            const select = document.getElementById('pen-filter-livestock');
            let options = '<option value="">-- Pilih Kandang --</option>';
            for (const pen of response.data.pens) {
                options += `<option value="${pen.id}">${escapeHtml(pen.name)} (${escapeHtml(pen.category)})</option>`;
            }
            select.innerHTML = options;
        }
    } catch(error) {
        console.error(error);
        document.getElementById('pen-filter-livestock').innerHTML = '<option value="">Gagal memuat kandang</option>';
    }
}

async function loadLivestockByPen() {
    const penId = document.getElementById('pen-filter-livestock').value;
    const tbody = document.getElementById('livestock-table-body');
    if (!penId) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-6 text-gray-400">Pilih kandang terlebih dahulu</td></tr>';
        return;
    }
    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-6 text-gray-400">Memuat...</td></tr>';
    try {
        const response = await TernakPark.api.fetchData(`/web-api/pens/livestock?pen_id=${penId}`);
        if (response.success) {
            const livestocks = response.data.livestocks || [];
            if (livestocks.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-6 text-gray-400">Tidak ada ternak di kandang ini</td></tr>';
                return;
            }
            let html = '';
            for (const livestock of livestocks) {
                html += `
                    <tr class="border-b border-white/10">
                        <td class="px-3 py-2">${escapeHtml(livestock.ear_tag)}</td>
                        <td class="px-3 py-2">${livestock.gender === 'male' ? 'Jantan' : 'Betina'}</td>
                        <td class="px-3 py-2">${escapeHtml(livestock.breed_type || '-')}</td>
                        <td class="px-3 py-2">${livestock.current_weight}</td>
                        <td class="px-3 py-2">${escapeHtml(livestock.condition || '-')}</td>
                        <td class="px-3 py-2">${escapeHtml(response.data.pen?.abk || '-')}</td>
                        <td class="px-3 py-2">${livestock.age_days ?? '-'}</td>
                    </tr>
                `;
            }
            tbody.innerHTML = html;
        } else {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-6 text-red-400">${escapeHtml(response.message || 'Gagal memuat data')}</td></tr>`;
        }
    } catch(error) {
        console.error(error);
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-6 text-red-400">Koneksi error</td></tr>';
    }
}

// ==================== LIST JUMLAH TERNAK & TOTAL BOBOT PER KANDANG ====================
async function loadPenSummary() {
    const tbody = document.getElementById('pen-summary-table-body');
    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-6 text-gray-400">Memuat data...</td></tr>';
    try {
        const response = await TernakPark.api.fetchData('/web-api/pens/livestock');
        if (response.success && response.data) {
            const data = response.data;
            if (!data || data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-6 text-gray-400">Belum ada data kandang</td></tr>';
                return;
            }
            let html = '';
            for (const pen of data) {
                const occupancyPercentage = pen.occupancy_percent ? pen.occupancy_percent : (pen.capacity ? ((pen.livestock_count / pen.capacity) * 100).toFixed(1) : 0);
                html += `
                    <tr class="border-b border-white/10">
                        <td class="px-3 py-2">${escapeHtml(pen.name)}</td>
                        <td class="px-3 py-2">${escapeHtml(pen.abk || '-')}</td>
                        <td class="px-3 py-2">${escapeHtml(pen.category)}</td>
                        <td class="px-3 py-2">${pen.livestock_count}</td>
                        <td class="px-3 py-2">${pen.total_weight}</td>
                        <td class="px-3 py-2">${pen.capacity}</td>
                        <td class="px-3 py-2">${occupancyPercentage}%</td>
                    </tr>
                `;
            }
            tbody.innerHTML = html;
        } else {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-6 text-red-400">${escapeHtml(response.message || 'Gagal memuat data')}</td></tr>`;
        }
    } catch(error) {
        console.error(error);
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-6 text-red-400">Koneksi error</td></tr>';
    }
}

// ==================== MODAL TAMBAH KANDANG ====================
function openAddPenModal() {
    document.getElementById('add-pen-modal').classList.remove('hidden');
}
function closeAddPenModal() {
    document.getElementById('add-pen-modal').classList.add('hidden');
    document.getElementById('add-pen-form').reset();
}
async function submitAddPenForm() {
    const form = document.getElementById('add-pen-form');
    const data = Object.fromEntries(new FormData(form));
    const submitButton = form.querySelector('button[type="button"]:last-child');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = 'Menyimpan...';
    try {
        const response = await fetch('/web-api/pens/store', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (response.ok && result.success) {
            closeAddPenModal();
            loadPensData();
            loadPenOptions();
            loadPenSummary();
            TernakPark.ui.showToast('Kandang berhasil ditambahkan', 'success');
        } else {
            TernakPark.ui.showToast(result.message || 'Gagal menambahkan kandang', 'error');
        }
    } catch (error) {
        console.error(error);
        TernakPark.ui.showToast('Koneksi error', 'error');
    } finally {
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
        if (typeof hideLoading === 'function') {
            hideLoading();
        }
    }
}

// ==================== MODAL EDIT KANDANG ====================
async function openEditPen(id) {
    try {
        const response = await TernakPark.api.fetchData(`/web-api/pens/${id}/detail`);
        if (response.success && response.data) {
            const pen = response.data;
            document.getElementById('edit_pen_id').value = pen.id;
            document.getElementById('edit_name').value = pen.name;
            document.getElementById('edit_code').value = pen.code || '';
            document.getElementById('edit_category').value = pen.category;
            document.getElementById('edit_abk').value = pen.abk || '';
            document.getElementById('edit_capacity').value = pen.capacity;
            document.getElementById('edit_status').value = pen.status;
            document.getElementById('edit-pen-modal').classList.remove('hidden');
        } else {
            TernakPark.ui.showToast('Gagal memuat data kandang', 'error');
        }
    } catch (error) {
        console.error(error);
        TernakPark.ui.showToast('Terjadi kesalahan', 'error');
    }
}
function closeEditPenModal() {
    document.getElementById('edit-pen-modal').classList.add('hidden');
}

// ==================== MODAL KONFIRMASI HAPUS ====================
function confirmDeletePen(id, name) {
    deletePenId = id;
    const messageEl = document.getElementById('delete-pen-message');
    if (messageEl) {
        messageEl.innerHTML = `Apakah Anda yakin ingin menghapus kandang <strong class="text-amber-300">"${escapeHtml(name)}"</strong>?<br>Tindakan ini tidak dapat dibatalkan.`;
    }
    const modal = document.getElementById('delete-pen-modal');
    const panel = document.getElementById('delete-pen-modal-panel');
    if (modal && panel) {
        modal.classList.remove('hidden');
        void modal.offsetWidth;
        panel.classList.remove('opacity-0', 'scale-95');
        panel.classList.add('opacity-100', 'scale-100');
    }
}

function closeDeletePenModal() {
    const modal = document.getElementById('delete-pen-modal');
    const panel = document.getElementById('delete-pen-modal-panel');
    if (modal && panel) {
        panel.classList.remove('opacity-100', 'scale-100');
        panel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            deletePenId = null;
        }, 200);
    }
}

async function executeDeletePen() {
    if (!deletePenId) return;
    try {
        const response = await fetch(`/web-api/pens/${deletePenId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const result = await response.json();
        if (response.ok && result.success) {
            TernakPark.ui.showToast('Kandang berhasil dihapus', 'success');
            closeDeletePenModal();
            loadPensData();
            loadPenOptions();
            loadPenSummary();
        } else {
            TernakPark.ui.showToast(result.message || 'Gagal menghapus kandang', 'error');
            closeDeletePenModal();
        }
    } catch (error) {
        console.error(error);
        TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
        closeDeletePenModal();
    } finally {
        if (typeof hideLoading === 'function') {
            hideLoading();
        }
    }
}

// ==================== MODAL IMPOR EXCEL ====================
function openImportPenModal() {
    document.getElementById('import-pen-modal').classList.remove('hidden');
}
function closeImportPenModal() {
    document.getElementById('import-pen-modal').classList.add('hidden');
    document.getElementById('import-pen-form').reset();
    document.getElementById('import-pen-progress').classList.add('hidden');
}

const importPenForm = document.getElementById('import-pen-form');
if (importPenForm) {
    importPenForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.disabled = true;
        document.getElementById('import-pen-progress').classList.remove('hidden');
        try {
            const response = await fetch('/web-api/pens/import', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            });
            const result = await response.json();
            if (response.ok && result.success) {
                TernakPark.ui.showToast('Data kandang berhasil diimpor: ' + (result.imported || 0) + ' record', 'success');
                closeImportPenModal();
                loadPensData();
                loadPenOptions();
                loadPenSummary();
            } else {
                TernakPark.ui.showToast(result.message || 'Gagal impor', 'error');
            }
        } catch (error) {
            console.error(error);
            TernakPark.ui.showToast('Koneksi error', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
            document.getElementById('import-pen-progress').classList.add('hidden');
            if (typeof hideLoading === 'function') {
                hideLoading();
            }
        }
    });
}

// ==================== UTILITIES ====================
function showError(message) {
    TernakPark.ui.showToast(message, 'error');
}
function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, function(match) {
        if (match === '&') return '&amp;';
        if (match === '<') return '&lt;';
        if (match === '>') return '&gt;';
        return match;
    });
}
</script>
@endpush