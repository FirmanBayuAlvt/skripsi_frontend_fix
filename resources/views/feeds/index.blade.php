@extends('layouts.app')

@section('title', 'Manajemen Pakan')
@section('header-title', 'Manajemen Pakan')

@section('page-header')
<div class="flex flex-wrap justify-between items-center gap-3">
    <div class="flex items-center gap-2">
        <div class="h-8 w-1 bg-gradient-to-b from-emerald-300 to-teal-500 rounded-full"></div>
        <p class="text-emerald-200 font-medium tracking-wide">MANAJEMEN PAKAN</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('feeds.usage') }}" class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white px-4 py-2 rounded-xl flex items-center shadow-md transition-all transform hover:scale-[1.02]">
            <i class="fas fa-chart-line mr-2"></i> Penggunaan Pakan
        </a>
        <a href="{{ route('feeds.procurement') }}" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-4 py-2 rounded-xl flex items-center shadow-md transition-all transform hover:scale-[1.02]">
            <i class="fas fa-truck mr-2"></i> Pengadaan Pakan
        </a>
        <a href="{{ route('feeds.requirements') }}" class="bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white px-4 py-2 rounded-xl flex items-center shadow-md transition-all transform hover:scale-[1.02]">
            <i class="fas fa-calculator mr-2"></i> Kebutuhan Pakan
        </a>
        <a href="{{ route('feeds.stock') }}" class="bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white px-4 py-2 rounded-xl flex items-center shadow-md transition-all transform hover:scale-[1.02]">
            <i class="fas fa-boxes mr-2"></i> Stok Pakan
        </a>
        <button onclick="openAddFeedModal()" class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-4 py-2 rounded-xl flex items-center shadow-md transition-all transform hover:scale-[1.02]">
            <i class="fas fa-plus mr-2"></i> Tambah Pakan
        </button>
        <button onclick="openImportFeedModal()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-xl flex items-center shadow-md transition-all transform hover:scale-[1.02]">
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
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="stat-card p-5">
            <div class="flex items-center">
                <div class="p-3 bg-emerald-500/20 rounded-2xl text-emerald-300"><i class="fas fa-seedling text-xl"></i></div>
                <div class="ml-4">
                    <p class="text-gray-300 text-sm font-medium">Jenis Pakan</p>
                    <p class="stat-value" id="total-feed-types">-</p>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex items-center">
                <div class="p-3 bg-emerald-500/20 rounded-2xl text-emerald-300"><i class="fas fa-warehouse text-xl"></i></div>
                <div class="ml-4">
                    <p class="text-gray-300 text-sm font-medium">Total Stok (kg)</p>
                    <p class="stat-value" id="total-stock">-</p>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-500/20 rounded-2xl text-yellow-300"><i class="fas fa-exclamation-triangle text-xl"></i></div>
                <div class="ml-4">
                    <p class="text-gray-300 text-sm font-medium">Stok Rendah</p>
                    <p class="stat-value" id="low-stock">-</p>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500/20 rounded-2xl text-blue-300"><i class="fas fa-money-bill-wave text-xl"></i></div>
                <div class="ml-4">
                    <p class="text-gray-300 text-sm font-medium">Nilai Stok</p>
                    <p class="stat-value" id="stock-value">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- DAFTAR PAKAN -->
    <div class="glass-card p-0 overflow-hidden">
        <div class="flex items-center gap-2 border-b border-white/10 p-5">
            <i class="fas fa-table-list text-emerald-400 text-xl"></i>
            <h3 class="font-bold text-white text-lg">Daftar Pakan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="custom-table w-full text-sm text-white">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr class="text-gray-300 text-xs uppercase">
                        <th class="px-4 py-3 text-left">Nama Pakan</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Stok (kg)</th>
                        <th class="px-4 py-3 text-left">Harga/kg</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </td>
                </thead>
                <tbody id="feeds-table-body">
                    <tr><td colspan="6" class="text-center py-8 text-gray-400">Memuat data...<\/td><\/tr>
                </tbody>
            </table>
        </div>
        <div id="feeds-pagination" class="p-4 border-t border-white/10 flex justify-between items-center"></div>
    </div>

    <!-- PAKAN BERDASARKAN KATEGORI KANDANG -->
    <div class="space-y-6">
        <h2 class="text-white text-xl font-bold">Pakan Berdasarkan Kategori Kandang</h2>
        <div id="pen-categories" class="grid grid-cols-1 lg:grid-cols-2 gap-6"></div>

        <div class="glass-card p-6">
            <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
                <i class="fas fa-chart-bar mr-2 text-emerald-400"></i>
                <h3 class="font-bold text-white text-lg">Grafik Kebutuhan Pakan per Kategori Kandang</h3>
            </div>
            <div class="w-full max-w-4xl mx-auto">
                <canvas id="categoryFeedChart" class="w-full" style="height: 320px;"></canvas>
            </div>
            <div id="chart-empty-message" class="text-center text-gray-400 hidden mt-4">Belum ada data kategori kandang untuk ditampilkan.</div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pakan -->
<div id="add-feed-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="bg-black/40 backdrop-blur-md rounded-2xl p-6 max-w-md w-full z-10 shadow-2xl border border-white/20">
            <h3 class="text-white text-xl font-bold mb-5">Tambah Pakan Baru</h3>
            <form id="add-feed-form" class="space-y-4 no-global-loading" data-no-global-loading="true">
                @csrf
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Nama Pakan *</label><input type="text" name="name" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-emerald-500"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Kategori *</label><select name="category" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"><option value="silase">Silase</option><option value="cf_jember">CF Jember</option><option value="jagung_halus">Jagung Halus</option><option value="konsentrat">Konsentrat</option><option value="hijauan">Hijauan</option><option value="konsentrat_buatan">Konsentrat Buatan</option><option value="mineral">Mineral</option><option value="vitamin">Vitamin</option></select></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Stok Awal (kg) *</label><input type="number" step="0.01" name="current_stock" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Harga per kg</label><input type="number" step="0.01" name="price_per_kg" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Satuan</label><input type="text" name="unit" value="kg" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Status</label><select name="is_active" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"><option value="1">Aktif</option><option value="0">Tidak Aktif</option></select></div>
                <div class="flex justify-end space-x-3 pt-4"><button type="button" onclick="closeAddFeedModal()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl">Batal</button><button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-xl shadow-md">Simpan</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Update Stok -->
<div id="update-stock-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="bg-black/40 backdrop-blur-md rounded-2xl p-6 max-w-md w-full z-10 shadow-2xl border border-white/20">
            <h3 class="text-white text-xl font-bold mb-5">Tambah Stok Pakan</h3>
            <form id="update-stock-form" class="space-y-4 no-global-loading" data-no-global-loading="true">
                @csrf
                <input type="hidden" name="feed_id" id="stock-feed-id">
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Nama Pakan</label><p id="stock-feed-name" class="text-white font-semibold">-</p></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Tambah Stok (kg) *</label><input type="number" step="0.01" name="add_stock_kg" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Harga per kg (opsional)</label><input type="number" step="0.01" name="price_per_kg" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white" placeholder="Kosongkan jika tidak berubah"></div>
                <div class="flex justify-end space-x-3 pt-4"><button type="button" onclick="closeUpdateStockModal()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl">Batal</button><button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-xl shadow-md">Tambah Stok</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pakan -->
<div id="edit-feed-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="bg-black/40 backdrop-blur-md rounded-2xl p-6 max-w-md w-full z-10 shadow-2xl border border-white/20">
            <h3 class="text-white text-xl font-bold mb-5">Edit Pakan</h3>
            <form id="edit-feed-form" class="space-y-4 no-global-loading" data-no-global-loading="true">
                @csrf
                <input type="hidden" name="id" id="edit-feed-id">
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Nama Pakan *</label><input type="text" name="name" id="edit-feed-name" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Kategori *</label><select name="category" id="edit-feed-category" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"><option value="silase">Silase</option><option value="cf_jember">CF Jember</option><option value="jagung_halus">Jagung Halus</option><option value="konsentrat">Konsentrat</option><option value="hijauan">Hijauan</option><option value="konsentrat_buatan">Konsentrat Buatan</option><option value="mineral">Mineral</option><option value="vitamin">Vitamin</option></select></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Stok (kg) *</label><input type="number" step="0.01" name="current_stock" id="edit-feed-stock" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Harga per kg</label><input type="number" step="0.01" name="price_per_kg" id="edit-feed-price" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Satuan</label><input type="text" name="unit" id="edit-feed-unit" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"></div>
                <div><label class="block text-gray-200 text-sm font-medium mb-1">Status</label><select name="is_active" id="edit-feed-active" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white"><option value="1">Aktif</option><option value="0">Tidak Aktif</option></select></div>
                <div class="flex justify-end space-x-3 pt-4"><button type="button" onclick="closeEditFeedModal()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl">Batal</button><button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-xl shadow-md">Simpan Perubahan</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Pakan (Aesthetic) -->
<div id="delete-feed-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"></div>
        <div class="bg-black/40 backdrop-blur-md rounded-2xl p-6 max-w-md w-full z-10 shadow-2xl border border-white/20 transform transition-all">
            <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-500/20 rounded-full"><i class="fas fa-trash-alt text-red-400 text-2xl"></i></div>
            <h3 class="text-xl font-bold text-white text-center mt-4">Konfirmasi Hapus</h3>
            <p class="text-gray-300 text-center mt-2" id="delete-feed-message">Apakah Anda yakin ingin menonaktifkan pakan?</p>
            <p class="text-gray-400 text-xs text-center mt-1">Data akan tetap tersimpan dengan status tidak aktif.</p>
            <div class="flex justify-end space-x-3 mt-6"><button type="button" onclick="closeDeleteFeedModal()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl transition">Batal</button><button type="button" id="confirm-delete-feed-btn" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl shadow-md transition">Hapus</button></div>
        </div>
    </div>
</div>

<!-- Modal Impor Excel -->
<div id="import-feed-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="bg-black/40 backdrop-blur-md rounded-2xl p-6 max-w-lg w-full z-10 shadow-2xl border border-white/20">
            <h3 class="text-white text-xl font-bold mb-5">Impor Data Pakan dari Excel</h3>
            <form id="import-feed-form" enctype="multipart/form-data" class="no-global-loading" data-no-global-loading="true">
                @csrf
                <div class="mb-4"><label class="block text-gray-200 text-sm font-medium mb-2">Pilih file Excel</label><input type="file" name="file" accept=".xlsx,.xls,.csv" required class="block w-full bg-white/10 border border-white/20 rounded-xl p-2 text-white"></div>
                <div class="mb-4"><p class="text-gray-300 text-sm">Format kolom: <code class="text-emerald-300">nama, kategori, stok_awal, harga_per_kg, satuan, aktif</code>. Baris pertama harus header.</p></div>
                <div class="flex justify-end space-x-3"><button type="button" onclick="closeImportFeedModal()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl">Batal</button><button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-xl shadow-md">Impor</button></div>
            </form>
            <div id="import-feed-progress" class="hidden mt-4 text-center"><div class="loading-spinner mx-auto"></div><p class="text-gray-400 text-sm mt-2">Memproses...</p></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let categoryFeedChart = null;
    let currentPage = 1;
    let feedsData = [];
    let feedToDelete = null;

    document.addEventListener('DOMContentLoaded', function() {
        loadFeedsData();
        loadRequirements();

        const addForm = document.getElementById('add-feed-form');
        if (addForm) addForm.addEventListener('submit', submitAddFeed);

        const updateStockForm = document.getElementById('update-stock-form');
        if (updateStockForm) updateStockForm.addEventListener('submit', submitUpdateStock);

        const editForm = document.getElementById('edit-feed-form');
        if (editForm) editForm.addEventListener('submit', submitEditFeed);

        const importForm = document.getElementById('import-feed-form');
        if (importForm) importForm.addEventListener('submit', submitImportFeed);

        const confirmBtn = document.getElementById('confirm-delete-feed-btn');
        if (confirmBtn) confirmBtn.addEventListener('click', executeDeleteFeed);
    });

    async function loadFeedsData(page = 1) {
        currentPage = page;
        try {
            const res = await TernakPark.api.fetchData(`/web-api/feeds/data?page=${page}&per_page=15`);
            if (res.success) {
                feedsData = res.data.feed_types;
                updateStatistics(res.data);
                renderFeedsTable(feedsData);
                renderFeedsPagination(res.data.pagination);
            } else {
                TernakPark.ui.showToast(res.message || 'Gagal memuat data pakan', 'error');
            }
        } catch (e) {
            console.error(e);
            TernakPark.ui.showToast('Koneksi error: ' + e.message, 'error');
        }
    }

    function updateStatistics(data) {
        document.getElementById('total-feed-types').innerText = data.total_types ?? 0;
        document.getElementById('total-stock').innerText = data.stock_summary?.total_stock_kg ?? 0;
        document.getElementById('low-stock').innerText = data.low_stock_count ?? 0;
        document.getElementById('stock-value').innerText = TernakPark.format.currency(data.stock_summary?.total_value ?? 0);
    }

    function renderFeedsTable(feeds) {
        const tbody = document.getElementById('feeds-table-body');
        if (!feeds || feeds.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-400">Tidak ada data pakan<\/td><\/tr>';
            return;
        }
        let html = '';
        for (const feed of feeds) {
            const isStockLow = feed.current_stock < 100;
            html += `
                <tr class="border-b border-white/10 hover:bg-white/5 transition">
                    <td class="px-4 py-3 font-medium">${escapeHtml(feed.name)}<\/td>
                    <td class="px-4 py-3">${escapeHtml(feed.category)}<\/td>
                    <td class="px-4 py-3 ${isStockLow ? 'text-red-300 font-semibold' : ''}">${feed.current_stock} kg<\/td>
                    <td class="px-4 py-3">${TernakPark.format.currency(feed.price_per_kg)}<\/td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs ${feed.is_active ? 'bg-emerald-500/20 text-emerald-200' : 'bg-red-500/20 text-red-200'}">${feed.is_active ? 'Aktif' : 'Tidak Aktif'}</span><\/td>
                    <td class="px-4 py-3 text-right">
                        <button onclick="openUpdateStockModal(${feed.id}, '${escapeHtml(feed.name)}')" class="text-blue-400 hover:text-blue-300 mr-2" title="Tambah Stok"><i class="fas fa-plus-circle"></i><\/button>
                        <button onclick="openEditFeedModal(${feed.id})" class="text-amber-400 hover:text-amber-300 mr-2" title="Edit"><i class="fas fa-edit"></i><\/button>
                        <button onclick="confirmDeleteFeed(${feed.id}, '${escapeHtml(feed.name)}')" class="text-red-400 hover:text-red-300" title="Hapus"><i class="fas fa-trash"></i><\/button>
                    <\/td>
                <\/tr>
            `;
        }
        tbody.innerHTML = html;
    }

    function renderFeedsPagination(pagination) {
        const container = document.getElementById('feeds-pagination');
        if (!pagination || pagination.total <= pagination.per_page) {
            container.innerHTML = '';
            return;
        }
        const start = (pagination.current_page - 1) * pagination.per_page + 1;
        const end = Math.min(pagination.current_page * pagination.per_page, pagination.total);
        let html = `<div class="text-sm text-gray-400">Menampilkan ${start} - ${end} dari ${pagination.total}</div>`;
        html += '<div class="flex space-x-2">';
        if (pagination.current_page > 1) {
            html += `<button onclick="loadFeedsData(${pagination.current_page - 1})" class="px-3 py-1 bg-white/10 rounded-xl hover:bg-white/20">Prev</button>`;
        }
        if (pagination.current_page < pagination.last_page) {
            html += `<button onclick="loadFeedsData(${pagination.current_page + 1})" class="px-3 py-1 bg-white/10 rounded-xl hover:bg-white/20">Next</button>`;
        }
        html += '</div>';
        container.innerHTML = html;
    }

    async function loadRequirements() {
        try {
            const res = await TernakPark.api.fetchData('/web-api/feeds/requirements');
            if (res.success) {
                const penCategories = res.data.pen_categories || [];
                const penCategoryFeeds = res.data.pen_category_feeds || {};
                if (penCategories.length === 0 && Object.keys(penCategoryFeeds).length === 0) {
                    document.getElementById('pen-categories').innerHTML = '<div class="text-center text-gray-400 col-span-full">Belum ada data kategori kandang. Silakan tambahkan kandang terlebih dahulu.</div>';
                    document.getElementById('chart-empty-message').classList.remove('hidden');
                    return;
                }
                renderPenCategories(penCategoryFeeds);
                if (penCategories.length > 0) {
                    initializeCategoryFeedChart(penCategories);
                    document.getElementById('chart-empty-message').classList.add('hidden');
                } else {
                    document.getElementById('chart-empty-message').classList.remove('hidden');
                }
            } else {
                document.getElementById('pen-categories').innerHTML = '<div class="text-center text-red-400 col-span-full">Gagal memuat rekomendasi pakan</div>';
                document.getElementById('chart-empty-message').classList.remove('hidden');
            }
        } catch (e) {
            console.error(e);
            document.getElementById('pen-categories').innerHTML = '<div class="text-center text-red-400 col-span-full">Koneksi error</div>';
            document.getElementById('chart-empty-message').classList.remove('hidden');
        }
    }

    function renderPenCategories(penCategoryFeeds) {
        const container = document.getElementById('pen-categories');
        const categories = Object.keys(penCategoryFeeds);
        if (categories.length === 0) {
            container.innerHTML = '<div class="text-center text-gray-400 col-span-full">Belum ada data kategori kandang</div>';
            return;
        }
        let html = '';
        for (const category of categories) {
            const feeds = penCategoryFeeds[category] || [];
            html += `
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 shadow-md p-6 hover:border-emerald-400/50 transition">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-white font-semibold text-lg">${escapeHtml(category)}</h3>
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-200 rounded-full text-xs">${feeds.length} jenis pakan</span>
                    </div>
                    <div class="space-y-3">${feeds.length ? feeds.map(feedName => `<div class="flex justify-between items-center p-3 bg-black/20 rounded-xl"><div><p class="font-medium text-white">${escapeHtml(feedName)}</p></div></div>`).join('') : '<p class="text-gray-400 text-center py-4">Belum ada data pakan untuk kategori ini</p>'}</div>
                </div>
            `;
        }
        container.innerHTML = html;
    }

    function initializeCategoryFeedChart(penCategories) {
        const canvas = document.getElementById('categoryFeedChart');
        if (!canvas) return;
        const labels = penCategories.map(cat => cat.category || cat.name || 'Kategori');
        const dataValues = penCategories.map(cat => Number(cat.daily_ration_kg) || 0);
        if (categoryFeedChart) categoryFeedChart.destroy();
        categoryFeedChart = new Chart(canvas, {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Kebutuhan Harian (kg)', data: dataValues, backgroundColor: '#10b981', borderRadius: 8, barPercentage: 0.65, categoryPercentage: 0.8 }] },
            options: {
                responsive: true, maintainAspectRatio: true,
                layout: { padding: { top: 10, bottom: 20, left: 10, right: 10 } },
                plugins: {
                    legend: { display: true, position: 'top', labels: { color: '#e2e8f0', font: { size: 12, weight: 'bold' }, boxWidth: 12, padding: 15 } },
                    tooltip: { backgroundColor: 'rgba(0,0,0,0.8)', titleColor: '#a7f3d0', bodyColor: '#cbd5e1', callbacks: { label: ctx => `Kebutuhan: ${ctx.raw.toFixed(2)} kg` } }
                },
                scales: {
                    x: { ticks: { color: '#e2e8f0', maxRotation: 35, minRotation: 35, font: { size: 11 } }, grid: { display: false } },
                    y: { beginAtZero: true, ticks: { color: '#e2e8f0', stepSize: 50, callback: value => value + ' kg' }, grid: { color: 'rgba(255,255,255,0.08)' }, title: { display: true, text: 'Kebutuhan Pakan (kg/hari)', color: '#94a3b8', font: { size: 12 } } }
                },
                interaction: { mode: 'index', intersect: false },
                elements: { bar: { borderWidth: 0 } }
            }
        });
    }

    function openAddFeedModal() { document.getElementById('add-feed-modal').classList.remove('hidden'); document.getElementById('add-feed-form').reset(); }
    function closeAddFeedModal() { document.getElementById('add-feed-modal').classList.add('hidden'); }
    async function submitAddFeed(e) {
        e.preventDefault();
        const form = e.target, formData = new FormData(form), data = Object.fromEntries(formData);
        data.is_active = data.is_active === '1';
        data.current_stock = parseFloat(data.current_stock) || 0;
        data.price_per_kg = data.price_per_kg ? parseFloat(data.price_per_kg) : null;
        delete data._token;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
        try {
            const res = await fetch('/web-api/feeds/store', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify(data) });
            const json = await res.json();
            if (json.success) {
                TernakPark.ui.showToast('Pakan berhasil ditambahkan', 'success');
                closeAddFeedModal();
                loadFeedsData();
                loadRequirements();
            } else {
                TernakPark.ui.showToast(json.message || 'Gagal menambahkan pakan', 'error');
            }
        } catch (err) { console.error(err); TernakPark.ui.showToast('Koneksi error: ' + err.message, 'error'); }
        finally { submitBtn.disabled = false; submitBtn.innerHTML = originalText; }
    }

    function openUpdateStockModal(id, name) { document.getElementById('stock-feed-id').value = id; document.getElementById('stock-feed-name').innerText = name; document.getElementById('update-stock-form').reset(); document.getElementById('update-stock-modal').classList.remove('hidden'); }
    function closeUpdateStockModal() { document.getElementById('update-stock-modal').classList.add('hidden'); }
    async function submitUpdateStock(e) {
        e.preventDefault();
        const form = e.target, formData = new FormData(form), data = Object.fromEntries(formData);
        data.add_stock_kg = parseFloat(data.add_stock_kg) || 0;
        data.price_per_kg = data.price_per_kg ? parseFloat(data.price_per_kg) : null;
        delete data._token;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
        try {
            const res = await fetch('/web-api/feeds/update-stock', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify(data) });
            const json = await res.json();
            if (json.success) {
                TernakPark.ui.showToast('Stok berhasil ditambahkan', 'success');
                closeUpdateStockModal();
                loadFeedsData();
                loadRequirements();
            } else {
                TernakPark.ui.showToast(json.message || 'Gagal update stok', 'error');
            }
        } catch (err) { console.error(err); TernakPark.ui.showToast('Koneksi error: ' + err.message, 'error'); }
        finally { submitBtn.disabled = false; submitBtn.innerHTML = originalText; }
    }

    async function openEditFeedModal(id) {
        try {
            const res = await TernakPark.api.fetchData('/web-api/feeds/data?per_page=1000');
            if (res.success && res.data.feed_types) {
                const feed = res.data.feed_types.find(f => f.id == id);
                if (feed) {
                    document.getElementById('edit-feed-id').value = feed.id;
                    document.getElementById('edit-feed-name').value = feed.name;
                    document.getElementById('edit-feed-category').value = feed.category;
                    document.getElementById('edit-feed-stock').value = feed.current_stock;
                    document.getElementById('edit-feed-price').value = feed.price_per_kg;
                    document.getElementById('edit-feed-unit').value = feed.unit;
                    document.getElementById('edit-feed-active').value = feed.is_active ? '1' : '0';
                    document.getElementById('edit-feed-modal').classList.remove('hidden');
                } else {
                    TernakPark.ui.showToast('Data pakan tidak ditemukan', 'error');
                }
            }
        } catch (err) { console.error(err); TernakPark.ui.showToast('Gagal memuat data pakan', 'error'); }
    }
    function closeEditFeedModal() { document.getElementById('edit-feed-modal').classList.add('hidden'); }
    async function submitEditFeed(e) {
        e.preventDefault();
        const form = e.target, formData = new FormData(form), data = Object.fromEntries(formData);
        data.is_active = data.is_active === '1';
        data.current_stock = parseFloat(data.current_stock) || 0;
        data.price_per_kg = data.price_per_kg ? parseFloat(data.price_per_kg) : null;
        delete data._token;
        const id = data.id; delete data.id;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
        try {
            const response = await fetch(`/web-api/feeds/update/${id}`, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify(data) });
            if (!response.ok) { let errorMsg = `HTTP ${response.status}`; try { const errorText = await response.text(); errorMsg = errorText.includes('<!DOCTYPE') ? 'Server mengembalikan halaman HTML. Mungkin endpoint tidak ditemukan atau session habis.' : errorText.substring(0, 200); } catch (ignored) { errorMsg = response.statusText; } throw new Error(errorMsg); }
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) { const text = await response.text(); console.error('Response bukan JSON:', text.substring(0, 200)); throw new Error('Server mengembalikan HTML. Mungkin endpoint tidak ditemukan atau session habis.'); }
            const json = await response.json();
            if (json.success) {
                TernakPark.ui.showToast('Pakan berhasil diperbarui', 'success');
                closeEditFeedModal();
                await loadFeedsData();
                await loadRequirements();
            } else {
                TernakPark.ui.showToast(json.message || 'Gagal update pakan', 'error');
            }
        } catch (err) { console.error(err); TernakPark.ui.showToast('Koneksi error: ' + err.message, 'error'); }
        finally { submitBtn.disabled = false; submitBtn.innerHTML = originalText; }
    }

    function confirmDeleteFeed(id, name) { feedToDelete = id; document.getElementById('delete-feed-message').innerHTML = `Apakah Anda yakin ingin menonaktifkan pakan <strong class="text-red-300">"${escapeHtml(name)}"</strong>?`; document.getElementById('delete-feed-modal').classList.remove('hidden'); }
    function closeDeleteFeedModal() { document.getElementById('delete-feed-modal').classList.add('hidden'); feedToDelete = null; }
    async function executeDeleteFeed() {
        if (!feedToDelete) return;
        const confirmBtn = document.getElementById('confirm-delete-feed-btn');
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menghapus...';
        try {
            const response = await fetch(`/web-api/feeds/delete/${feedToDelete}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
            if (!response.ok) { let errorMsg = `HTTP ${response.status}`; try { const errorText = await response.text(); errorMsg = errorText.includes('<!DOCTYPE') ? 'Server mengembalikan halaman HTML. Mungkin endpoint tidak ditemukan atau session habis.' : errorText.substring(0, 200); } catch (ignored) { errorMsg = response.statusText; } throw new Error(errorMsg); }
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) { const text = await response.text(); console.error('Response bukan JSON:', text.substring(0, 200)); throw new Error('Server mengembalikan HTML. Mungkin endpoint tidak ditemukan atau session habis.'); }
            const json = await response.json();
            if (json.success) {
                TernakPark.ui.showToast('Pakan berhasil dinonaktifkan', 'success');
                closeDeleteFeedModal();
                await loadFeedsData();
                await loadRequirements();
            } else {
                TernakPark.ui.showToast(json.message || 'Gagal menonaktifkan pakan', 'error');
            }
        } catch (err) { console.error(err); TernakPark.ui.showToast('Koneksi error: ' + err.message, 'error'); }
        finally { confirmBtn.disabled = false; confirmBtn.innerHTML = 'Hapus'; feedToDelete = null; }
    }

    function openImportFeedModal() { document.getElementById('import-feed-modal').classList.remove('hidden'); document.getElementById('import-feed-form').reset(); document.getElementById('import-feed-progress').classList.add('hidden'); }
    function closeImportFeedModal() { document.getElementById('import-feed-modal').classList.add('hidden'); }
    async function submitImportFeed(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        document.getElementById('import-feed-progress').classList.remove('hidden');
        try {
            const response = await fetch('/web-api/feeds/import', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: formData });
            const json = await response.json();
            if (json.success) {
                TernakPark.ui.showToast(`Data pakan berhasil diimpor: ${json.imported || 0} record`, 'success');
                closeImportFeedModal();
                loadFeedsData();
                loadRequirements();
            } else {
                TernakPark.ui.showToast(json.message || 'Gagal impor data', 'error');
            }
        } catch (err) { console.error(err); TernakPark.ui.showToast('Koneksi error: ' + err.message, 'error'); }
        finally { submitBtn.disabled = false; submitBtn.innerHTML = originalText; document.getElementById('import-feed-progress').classList.add('hidden'); }
    }

    function escapeHtml(str) { if (!str) return ''; return String(str).replace(/[&<>]/g, m => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;' }[m])); }
</script>
@endpush