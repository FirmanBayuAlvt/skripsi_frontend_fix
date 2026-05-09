@extends('layouts.app')

@section('title', 'Aktivitas Logbook')
@section('header-title', 'Aktivitas Logbook')

@section('content')
<div class="space-y-6">
    <!-- Tombol Kembali ke Dashboard -->
    <div class="flex justify-end">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl transition duration-200">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Filter Section Premium -->
    <div class="glass-card p-5">
        <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
            <i class="fas fa-filter text-emerald-400"></i>
            <h3 class="font-bold text-white text-lg">Filter Aktivitas Logbook</h3>
        </div>
        <form id="logbook-filter" class="grid grid-cols-1 md:grid-cols-5 gap-4" data-no-global-loading>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-1">Tagging</label>
                <input type="text" id="filter-tagging" name="tagging" placeholder="Cari ear tag..." class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white placeholder:text-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-1">Tanggal Mulai</label>
                <input type="date" id="filter-start-date" name="start_date" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-1">Tanggal Akhir</label>
                <input type="date" id="filter-end-date" name="end_date" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-1">Aktivitas</label>
                <select id="filter-event" name="aktivitas" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="">Semua Aktivitas</option>
                    <option>Vaksin</option>
                    <option>Pindah Kandang</option>
                    <option>Domba Masuk</option>
                    <option>Ganti Tag</option>
                    <option>Sakit</option>
                    <option>Melahirkan</option>
                    <option>Mati</option>
                    <option>Terjual</option>
                    <option>Lepas Sapih</option>
                    <option>Timbang 30 hari</option>
                    <option>Timbang 60 hari</option>
                    <option>Timbang 90 hari</option>
                    <option>Timbang 100 hari</option>
                    <option>Timbang 180 hari</option>
                    <option>Hamil</option>
                    <option>Rekam IB</option>
                    <option>Birahi</option>
                    <option>Timbang 360 hari</option>
                    <option>Disembelih</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-4 py-2 rounded-xl shadow-md transition-all transform hover:scale-[1.02]">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Logbook Premium -->
    <div class="glass-card p-0 overflow-hidden">
        <div class="flex items-center justify-between gap-2 border-b border-white/10 p-5">
            <div class="flex items-center gap-2">
                <i class="fas fa-history text-emerald-400 text-xl"></i>
                <h3 class="font-bold text-white text-lg">Riwayat Aktivitas</h3>
            </div>
            <button id="btn-add-logbook" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm flex items-center gap-2 transition duration-200">
                <i class="fas fa-plus-circle"></i> Tambah Catatan
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="custom-table w-full text-sm text-white">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Tanggal Aktivitas</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Tagging</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Jenis Ternak</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Kandang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Kelamin</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Aktivitas</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Keterangan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Penanganan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Tag Baru</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Kandang Baru</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Kategori Baru</th>
                    </tr>
                </thead>
                <tbody id="logbook-table-body">
                    <tr>
                        <td colspan="11" class="text-center py-8 text-gray-400">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="pagination" class="p-5 border-t border-white/10 flex justify-between items-center"></div>
    </div>
</div>

<!-- Modal Tambah Catatan Logbook -->
<div id="add-logbook-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm"></div>
        <div class="relative bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 max-w-2xl w-full z-10 shadow-2xl border border-white/20">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white">Tambah Catatan Aktivitas</h3>
                <button type="button" onclick="closeAddLogbookModal()" class="text-gray-400 hover:text-white transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="add-logbook-form" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-1">Pilih Ternak *</label>
                        <select name="livestock_id" id="logbook-livestock" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white">
                            <option value="">-- Pilih Ternak --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-1">Tanggal Aktivitas *</label>
                        <input type="date" name="event_date" id="logbook-date" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white">
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-1">Aktivitas *</label>
                        <select name="event_type" id="logbook-event" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white">
                            <option value="">-- Pilih Aktivitas --</option>
                            <option>Vaksin</option>
                            <option>Pindah Kandang</option>
                            <option>Domba Masuk</option>
                            <option>Ganti Tag</option>
                            <option>Sakit</option>
                            <option>Melahirkan</option>
                            <option>Mati</option>
                            <option>Terjual</option>
                            <option>Lepas Sapih</option>
                            <option>Timbang 30 hari</option>
                            <option>Timbang 60 hari</option>
                            <option>Timbang 90 hari</option>
                            <option>Timbang 100 hari</option>
                            <option>Timbang 180 hari</option>
                            <option>Hamil</option>
                            <option>Rekam IB</option>
                            <option>Birahi</option>
                            <option>Timbang 360 hari</option>
                            <option>Disembelih</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-1">Penanganan</label>
                        <input type="text" name="handling" placeholder="Penanganan (opsional)" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-300 text-sm font-medium mb-1">Keterangan</label>
                    <textarea name="description" rows="3" placeholder="Deskripsi detail..." class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-1">Tag Baru (jika ganti tag)</label>
                        <input type="text" name="new_tag" placeholder="Tag baru" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white">
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-1">Kandang Baru</label>
                        <select name="new_pen_id" id="logbook-new-pen" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white">
                            <option value="">-- Pilih Kandang Baru --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-1">Kategori Baru (opsional)</label>
                        <input type="text" name="new_pen_category" placeholder="Kategori baru" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeAddLogbookModal()" class="px-4 py-2 bg-white/10 rounded-xl hover:bg-white/20 transition text-white">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 rounded-xl hover:bg-emerald-700 transition text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ==================== VARIABLES ====================
    let currentPage = 1;

    // ==================== DOM EVENT LISTENERS ====================
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof TernakPark === 'undefined') {
            console.error('TernakPark global object not found');
            return;
        }

        loadLogbookData();
        loadLivestockOptions();
        loadPenOptions();

        const filterForm = document.getElementById('logbook-filter');
        if (filterForm) {
            filterForm.addEventListener('submit', function(event) {
                event.preventDefault();
                currentPage = 1;
                loadLogbookData();
            });
        }

        const btnAddLogbook = document.getElementById('btn-add-logbook');
        if (btnAddLogbook) {
            btnAddLogbook.addEventListener('click', function() {
                openAddLogbookModal();
            });
        }

        const addForm = document.getElementById('add-logbook-form');
        if (addForm) {
            addForm.addEventListener('submit', submitAddLogbook);
        }
    });

    // ==================== LOAD DATA LOGBOOK ====================
    async function loadLogbookData() {
        const tagging = document.getElementById('filter-tagging')?.value || '';
        const startDate = document.getElementById('filter-start-date')?.value || '';
        const endDate = document.getElementById('filter-end-date')?.value || '';
        const aktivitas = document.getElementById('filter-event')?.value || '';

        const params = new URLSearchParams({
            tagging: tagging,
            start_date: startDate,
            end_date: endDate,
            kejadian: aktivitas,
            page: currentPage,
            per_page: 20
        });

        const tableBody = document.getElementById('logbook-table-body');
        tableBody.innerHTML = '<td><td colspan="11" class="text-center py-8 text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat data...<\/td><\/tr>';

        try {
            const response = await TernakPark.api.fetchData(`/web-api/logbook?${params.toString()}`);
            if (response.success) {
                renderLogbookTable(response.data);
                renderPagination(response.pagination);
            } else {
                const errorMessage = response.message || 'Gagal memuat data';
                tableBody.innerHTML = `<tr><td colspan="11" class="text-center py-8 text-red-400">${escapeHtml(errorMessage)}<\/td><\/tr>`;
                TernakPark.ui.showToast(errorMessage, 'error');
            }
        } catch (error) {
            console.error(error);
            tableBody.innerHTML = `<tr><td colspan="11" class="text-center py-8 text-red-400">Koneksi error: ${escapeHtml(error.message)}<\/td><\/tr>`;
            TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
        }
    }

    // ==================== RENDER TABEL ====================
    function renderLogbookTable(records) {
        const tableBody = document.getElementById('logbook-table-body');
        if (!records || records.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="11" class="text-center py-8 text-gray-400">Tidak ada data logbook<\/td><\/tr>';
            return;
        }

        let html = '';
        for (const record of records) {
            let formattedDate = record.tanggal_kejadian ? new Date(record.tanggal_kejadian).toLocaleString('id-ID') : '-';
            html += `
                <tr class="border-b border-white/10 hover:bg-white/5 transition">
                    <td class="px-4 py-3">${escapeHtml(formattedDate)}<\/td>
                    <td class="px-4 py-3 font-medium">${escapeHtml(record.tagging)}<\/td>
                    <td class="px-4 py-3">${escapeHtml(record.jenis_ternak)}<\/td>
                    <td class="px-4 py-3">${escapeHtml(record.kandang)}<\/td>
                    <td class="px-4 py-3">${escapeHtml(record.kelamin)}<\/td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-200 text-xs">${escapeHtml(record.kejadian)}<\/span><\/td>
                    <td class="px-4 py-3">${escapeHtml(record.keterangan)}<\/td>
                    <td class="px-4 py-3">${escapeHtml(record.penanganan)}<\/td>
                    <td class="px-4 py-3">${escapeHtml(record.tag_baru)}<\/td>
                    <td class="px-4 py-3">${escapeHtml(record.kandang_baru)}<\/td>
                    <td class="px-4 py-3">${escapeHtml(record.kategori_baru)}<\/td>
                <\/tr>
            `;
        }
        tableBody.innerHTML = html;
    }

    // ==================== RENDER PAGINATION ====================
    function renderPagination(pagination) {
        const paginationContainer = document.getElementById('pagination');
        if (!paginationContainer) return;

        if (!pagination || pagination.total <= pagination.per_page) {
            paginationContainer.innerHTML = '';
            return;
        }

        const startItem = (pagination.current_page - 1) * pagination.per_page + 1;
        const endItem = Math.min(pagination.current_page * pagination.per_page, pagination.total);

        let paginationHtml = `<div class="text-sm text-gray-400">Menampilkan ${startItem} - ${endItem} dari ${pagination.total}</div>`;
        paginationHtml += '<div class="flex space-x-2">';
        if (pagination.current_page > 1) {
            paginationHtml += `<button onclick="changePage(${pagination.current_page - 1})" class="px-3 py-1 bg-white/10 rounded-xl hover:bg-white/20 transition">Prev<\/button>`;
        }
        if (pagination.current_page < pagination.last_page) {
            paginationHtml += `<button onclick="changePage(${pagination.current_page + 1})" class="px-3 py-1 bg-white/10 rounded-xl hover:bg-white/20 transition">Next<\/button>`;
        }
        paginationHtml += '<\/div>';
        paginationContainer.innerHTML = paginationHtml;
    }

    // ==================== CHANGE PAGE ====================
    function changePage(page) {
        currentPage = page;
        loadLogbookData();
    }

    // ==================== LOAD LIVESTOCK OPTIONS ====================
    async function loadLivestockOptions() {
        try {
            const res = await TernakPark.api.fetchData('/web-api/livestocks/data?per_page=500');
            const select = document.getElementById('logbook-livestock');
            if (res.success && res.data.livestocks && res.data.livestocks.length > 0) {
                let options = '<option value="">-- Pilih Ternak --</option>';
                for (let i = 0; i < res.data.livestocks.length; i++) {
                    const l = res.data.livestocks[i];
                    options += `<option value="${l.id}">${escapeHtml(l.ear_tag)} (${escapeHtml(l.breed_type)})</option>`;
                }
                select.innerHTML = options;
            } else {
                select.innerHTML = '<option value="">Tidak ada data ternak</option>';
            }
        } catch (e) {
            console.error(e);
            document.getElementById('logbook-livestock').innerHTML = '<option value="">Gagal memuat data</option>';
        }
    }

    // ==================== LOAD PEN OPTIONS ====================
    async function loadPenOptions() {
        try {
            const res = await TernakPark.api.fetchData('/web-api/pens/data?per_page=500');
            const select = document.getElementById('logbook-new-pen');
            if (res.success && res.data.pens && res.data.pens.length > 0) {
                let options = '<option value="">-- Pilih Kandang Baru --</option>';
                for (let i = 0; i < res.data.pens.length; i++) {
                    const p = res.data.pens[i];
                    options += `<option value="${p.id}">${escapeHtml(p.name)} (${escapeHtml(p.category)})</option>`;
                }
                select.innerHTML = options;
            } else {
                select.innerHTML = '<option value="">Tidak ada data kandang</option>';
            }
        } catch (e) {
            console.error(e);
            document.getElementById('logbook-new-pen').innerHTML = '<option value="">Gagal memuat data</option>';
        }
    }

    // ==================== MODAL CONTROLS ====================
    function openAddLogbookModal() {
        const modal = document.getElementById('add-logbook-modal');
        if (modal) {
            modal.classList.remove('hidden');
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('logbook-date').value = today;
        }
    }

    function closeAddLogbookModal() {
        const modal = document.getElementById('add-logbook-modal');
        if (modal) modal.classList.add('hidden');
    }

    // ==================== SUBMIT ADD LOGBOOK ====================
    async function submitAddLogbook(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('/web-api/logbook/store', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                TernakPark.ui.showToast('Catatan logbook berhasil ditambahkan', 'success');
                closeAddLogbookModal();
                loadLogbookData();
                form.reset();
            } else {
                TernakPark.ui.showToast(result.message || 'Gagal menambahkan catatan', 'error');
            }
        } catch (error) {
            console.error(error);
            TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
        }
    }

    // ==================== UTILITIES ====================
    function escapeHtml(str) {
        if (!str) return '-';
        return String(str).replace(/[&<>]/g, function(match) {
            if (match === '&') return '&amp;';
            if (match === '<') return '&lt;';
            if (match === '>') return '&gt;';
            return match;
        });
    }
</script>
@endpush