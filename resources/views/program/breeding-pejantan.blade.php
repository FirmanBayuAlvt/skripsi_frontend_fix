@extends('layouts.app')

@section('title', 'Breeding - Pejantan')
@section('header-title', 'Manajemen Pejantan')

@section('content')
<div class="space-y-6">
    {{-- Tombol Kembali --}}
    <div class="flex justify-end">
        <a href="{{ route('program.breeding') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Breeding
        </a>
    </div>

    {{-- Kartu Statistik Pejantan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div><p class="text-gray-300 text-sm">Total Pejantan</p><p class="stat-value" id="total-pejantan">-</p></div>
                <div class="bg-emerald-500/20 p-3 rounded-2xl"><i class="fas fa-male text-emerald-300 text-xl"></i></div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div><p class="text-gray-300 text-sm">Pejantan Aktif</p><p class="stat-value" id="aktif-count">-</p></div>
                <div class="bg-green-500/20 p-3 rounded-2xl"><i class="fas fa-check-circle text-green-300 text-xl"></i></div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div><p class="text-gray-300 text-sm">Pejantan Tidak Aktif</p><p class="stat-value" id="nonaktif-count">-</p></div>
                <div class="bg-red-500/20 p-3 rounded-2xl"><i class="fas fa-ban text-red-300 text-xl"></i></div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div><p class="text-gray-300 text-sm">Rata-rata BB (kg)</p><p class="stat-value" id="avg-bb">-</p></div>
                <div class="bg-purple-500/20 p-3 rounded-2xl"><i class="fas fa-weight-scale text-purple-300 text-xl"></i></div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div><p class="text-gray-300 text-sm">Rata-rata Umur (hari)</p><p class="stat-value" id="avg-umur">-</p></div>
                <div class="bg-blue-500/20 p-3 rounded-2xl"><i class="fas fa-calendar-alt text-blue-300 text-xl"></i></div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div><p class="text-gray-300 text-sm">Pejantan di Kandang Kawin</p><p class="stat-value" id="kandang-kawin">-</p></div>
                <div class="bg-pink-500/20 p-3 rounded-2xl"><i class="fas fa-heart text-pink-300 text-xl"></i></div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div><p class="text-gray-300 text-sm">Pejantan di Karantina</p><p class="stat-value" id="karantina-count">-</p></div>
                <div class="bg-yellow-500/20 p-3 rounded-2xl"><i class="fas fa-shield-virus text-yellow-300 text-xl"></i></div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div><p class="text-gray-300 text-sm">Rata-rata ADG (kg/hari)</p><p class="stat-value" id="avg-adg">-</p></div>
                <div class="bg-indigo-500/20 p-3 rounded-2xl"><i class="fas fa-chart-line text-indigo-300 text-xl"></i></div>
            </div>
        </div>
    </div>

    {{-- Grafik Distribusi Pejantan --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-card p-5">
            <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
                <i class="fas fa-chart-pie text-emerald-400"></i>
                <h3 class="font-bold text-white text-lg">Status Pejantan</h3>
            </div>
            <canvas id="statusChart" height="250" class="w-full" style="max-height: 250px;"></canvas>
        </div>
        <div class="glass-card p-5">
            <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
                <i class="fas fa-chart-bar text-emerald-400"></i>
                <h3 class="font-bold text-white text-lg">Distribusi Berdasarkan Jenis Domba</h3>
            </div>
            <canvas id="breedChart" height="250" class="w-full" style="max-height: 250px;"></canvas>
        </div>
    </div>

    {{-- Filter Data Pejantan --}}
    <div class="glass-card p-5">
        <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
            <i class="fas fa-filter text-emerald-400"></i>
            <h3 class="font-bold text-white text-lg">Filter Data Pejantan</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="text-gray-300 text-sm">Status</label>
                <select id="filterStatus" class="w-full bg-white/10 border border-white/20 rounded-xl px-3 py-2 text-white focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua</option>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
            <div>
                <label class="text-gray-300 text-sm">Jenis Domba</label>
                <select id="filterBreed" class="w-full bg-white/10 border border-white/20 rounded-xl px-3 py-2 text-white focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua</option>
                </select>
            </div>
            <div>
                <label class="text-gray-300 text-sm">Kandang</label>
                <select id="filterPen" class="w-full bg-white/10 border border-white/20 rounded-xl px-3 py-2 text-white focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua</option>
                </select>
            </div>
            <div class="flex items-end">
                <button id="btnApplyFilter" class="w-full bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-4 py-2 rounded-xl shadow-md transition transform hover:scale-[1.02]">
                    <i class="fas fa-search mr-1"></i> Terapkan Filter
                </button>
            </div>
        </div>
    </div>

    {{-- Tabel Data Pejantan --}}
    <div class="glass-card p-0 overflow-hidden">
        <div class="flex items-center gap-2 border-b border-white/10 p-5">
            <i class="fas fa-table-list text-emerald-400"></i>
            <h3 class="font-bold text-white text-lg">Data Pejantan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="custom-table w-full text-sm">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr class="text-gray-300 text-xs uppercase">
                        <th class="px-3 py-2 text-left">Tagging</th>
                        <th class="px-3 py-2 text-left">Jenis Domba</th>
                        <th class="px-3 py-2 text-left">BB (kg)</th>
                        <th class="px-3 py-2 text-left">Umur (hari)</th>
                        <th class="px-3 py-2 text-left">ADG (kg/hari)</th>
                        <th class="px-3 py-2 text-left">Kandang</th>
                        <th class="px-3 py-2 text-left">Status</th>
                        <th class="px-3 py-2 text-left">Kondisi</th>
                        <th class="px-3 py-2 text-right">Aksi</th>
                     </tr>
                </thead>
                <tbody id="pejantan-table-body">
                    <tr>
                        <td colspan="9" class="text-center py-8 text-gray-400">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="pagination" class="p-5 border-t border-white/10 flex justify-between items-center"></div>
    </div>
</div>

{{-- Modal Detail Pejantan --}}
<div id="modalDetailPejantan" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"></div>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-black/40 backdrop-blur-md rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-white/20">
            <div class="px-6 pt-6 pb-4 sm:px-8 sm:pt-8">
                <div class="flex justify-between items-center border-b border-white/10 pb-3 mb-4">
                    <h3 class="text-xl font-bold text-white">📋 Detail Pejantan</h3>
                    <button class="close-modal text-gray-400 hover:text-white text-2xl leading-none">&times;</button>
                </div>
                <div id="detailPejantanInfo" class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm bg-white/5 rounded-xl p-4 border border-white/10"></div>
                <div class="mt-6">
                    <h4 class="font-semibold text-white mb-2">📈 Riwayat Berat Badan</h4>
                    <canvas id="weightHistoryChart" height="200" class="w-full bg-white/5 rounded-xl p-2"></canvas>
                </div>
                <div class="mt-6">
                    <h4 class="font-semibold text-white mb-2">📋 Riwayat Perkawinan</h4>
                    <div class="overflow-x-auto">
                        <table class="custom-table w-full text-sm">
                            <thead class="bg-white/5">
                                <tr class="text-gray-300 text-xs">
                                    <th class="px-3 py-2 text-left">Tanggal Kawin</th>
                                    <th class="px-3 py-2 text-left">Betina</th>
                                    <th class="px-3 py-2 text-left">Kandang</th>
                                    <th class="px-3 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody id="riwayatKawinTable"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-white/5 sm:flex sm:flex-row-reverse sm:px-8">
                <button class="close-modal px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ==================== VARIABLES GLOBAL ====================
let statusChart = null;
let breedChart = null;
let weightHistoryChart = null;
let currentPage = 1;
let filters = {};

// ==================== DOM READY ====================
document.addEventListener('DOMContentLoaded', function() {
    loadPejantanData();
    loadFilterOptions();

    // Event listener untuk tombol filter
    const applyFilterBtn = document.getElementById('btnApplyFilter');
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function() {
            filters = {
                status: document.getElementById('filterStatus').value,
                breed: document.getElementById('filterBreed').value,
                pen: document.getElementById('filterPen').value
            };
            currentPage = 1;
            loadPejantanData();
        });
    }

    // Event listener untuk menutup modal
    const closeModalButtons = document.querySelectorAll('#modalDetailPejantan .close-modal');
    closeModalButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const modal = document.getElementById('modalDetailPejantan');
            modal.classList.add('hidden');
            if (weightHistoryChart) {
                weightHistoryChart.destroy();
                weightHistoryChart = null;
            }
        });
    });
});

// ==================== LOAD FILTER OPTIONS ====================
async function loadFilterOptions() {
    try {
        const [livestockResponse, penResponse] = await Promise.all([
            TernakPark.api.fetchData('/web-api/livestocks/data?per_page=1000&gender=male'),
            TernakPark.api.fetchData('/web-api/pens/data')
        ]);

        const breedSelect = document.getElementById('filterBreed');
        if (breedSelect) {
            breedSelect.innerHTML = '<option value="">Semua</option>';
            if (livestockResponse.success && livestockResponse.data.livestocks) {
                const breeds = [...new Set(livestockResponse.data.livestocks.map(function(l) {
                    return l.breed_type;
                }))];
                breeds.forEach(function(breed) {
                    breedSelect.innerHTML += '<option value="' + escapeHtml(breed) + '">' + escapeHtml(breed.replace(/_/g, ' ')) + '</option>';
                });
            }
        }

        const penSelect = document.getElementById('filterPen');
        if (penSelect) {
            penSelect.innerHTML = '<option value="">Semua</option>';
            if (penResponse.success && penResponse.data.pens) {
                penResponse.data.pens.forEach(function(pen) {
                    penSelect.innerHTML += '<option value="' + pen.id + '">' + escapeHtml(pen.name) + '</option>';
                });
            }
        }
    } catch(error) {
        console.error('Error loading filter options:', error);
        if (typeof TernakPark !== 'undefined' && TernakPark.ui) {
            TernakPark.ui.showToast('Gagal memuat opsi filter', 'error');
        }
    }
}

// ==================== LOAD PEJANTAN DATA ====================
async function loadPejantanData() {
    try {
        let url = '/web-api/program/breeding/pejantan?page=' + currentPage;
        if (filters.status) url += '&status=' + encodeURIComponent(filters.status);
        if (filters.breed) url += '&breed=' + encodeURIComponent(filters.breed);
        if (filters.pen) url += '&pen=' + encodeURIComponent(filters.pen);

        const response = await TernakPark.api.fetchData(url);
        if (response.success) {
            const data = response.data;

            document.getElementById('total-pejantan').innerText = data.total_pejantan || 0;
            document.getElementById('aktif-count').innerText = data.aktif_count || 0;
            document.getElementById('nonaktif-count').innerText = data.nonaktif_count || 0;
            document.getElementById('avg-bb').innerText = (data.avg_bb || 0).toFixed(2);
            document.getElementById('avg-umur').innerText = (data.avg_umur || 0).toFixed(0);
            document.getElementById('kandang-kawin').innerText = data.kandang_kawin || 0;
            document.getElementById('karantina-count').innerText = data.karantina_count || 0;
            document.getElementById('avg-adg').innerText = (data.avg_adg || 0).toFixed(3);

            const statusCanvas = document.getElementById('statusChart');
            if (statusCanvas) {
                if (statusChart) statusChart.destroy();
                statusChart = new Chart(statusCanvas, {
                    type: 'pie',
                    data: {
                        labels: ['Aktif', 'Tidak Aktif'],
                        datasets: [{
                            data: [data.aktif_count || 0, data.nonaktif_count || 0],
                            backgroundColor: ['#10b981', '#ef4444'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'bottom', labels: { color: '#e2e8f0' } }
                        }
                    }
                });
            }

            const breedCanvas = document.getElementById('breedChart');
            if (breedCanvas) {
                if (breedChart) breedChart.destroy();
                breedChart = new Chart(breedCanvas, {
                    type: 'bar',
                    data: {
                        labels: data.breed_labels || [],
                        datasets: [{
                            label: 'Jumlah',
                            data: data.breed_counts || [],
                            backgroundColor: '#f59e0b',
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'top', labels: { color: '#e2e8f0' } }
                        },
                        scales: {
                            x: { ticks: { color: '#e2e8f0', maxRotation: 35, minRotation: 35 }, grid: { display: false } },
                            y: { ticks: { color: '#e2e8f0' }, grid: { color: 'rgba(255,255,255,0.05)' } }
                        }
                    }
                });
            }

            renderPejantanTable(data.pejantan || []);
            renderPagination(data.pagination);
        } else {
            if (typeof TernakPark !== 'undefined' && TernakPark.ui) {
                TernakPark.ui.showToast(response.message || 'Gagal memuat data pejantan', 'error');
            }
        }
    } catch(error) {
        console.error('Error loading pejantan data:', error);
        if (typeof TernakPark !== 'undefined' && TernakPark.ui) {
            TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
        }
    }
}

// ==================== RENDER TABEL PEJANTAN ====================
function renderPejantanTable(pejantan) {
    const tableBody = document.getElementById('pejantan-table-body');
    if (!tableBody) return;

    if (!pejantan || pejantan.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="9" class="text-center py-8 text-gray-400">Tidak ada数据<\/tr>';
        return;
    }

    let html = '';
    for (let i = 0; i < pejantan.length; i++) {
        const item = pejantan[i];
        html += '<tr class="border-b border-white/10 hover:bg-white/5 transition">';
        html += '<td class="px-3 py-2 font-medium">' + escapeHtml(item.ear_tag) + '<\/td>';
        html += '<td class="px-3 py-2">' + escapeHtml(item.breed_type) + '<\/td>';
        html += '<td class="px-3 py-2">' + (item.current_weight || '-') + '<\/td>';
        html += '<td class="px-3 py-2">' + (item.age_days || '-') + ' hari<\/td>';
        html += '<td class="px-3 py-2">' + (item.average_daily_gain ? item.average_daily_gain.toFixed(3) : '-') + '<\/td>';
        html += '<td class="px-3 py-2">' + escapeHtml(item.pen_name || '-') + '<\/td>';
        html += '<td class="px-3 py-2"><span class="px-2 py-1 rounded-full text-xs ' + (item.status ? 'bg-emerald-500/20 text-emerald-200' : 'bg-red-500/20 text-red-200') + '">' + (item.status ? 'Aktif' : 'Tidak Aktif') + '<\/span><\/td>';
        html += '<td class="px-3 py-2">' + escapeHtml(item.condition || '-') + '<\/td>';
        html += '<td class="px-3 py-2 text-right"><button class="btn-detail text-emerald-400 hover:text-emerald-300" data-id="' + item.id + '"><i class="fas fa-eye"></i> Detail<\/button><\/td>';
        html += '<\/tr>';
    }
    tableBody.innerHTML = html;

    const detailButtons = document.querySelectorAll('.btn-detail');
    detailButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const livestockId = this.getAttribute('data-id');
            if (livestockId) {
                loadDetailPejantan(livestockId);
            }
        });
    });
}

// ==================== RENDER PAGINATION ====================
function renderPagination(pagination) {
    const container = document.getElementById('pagination');
    if (!container) return;

    if (!pagination || pagination.total <= pagination.per_page) {
        container.innerHTML = '';
        return;
    }

    const start = (pagination.current_page - 1) * pagination.per_page + 1;
    const end = Math.min(pagination.current_page * pagination.per_page, pagination.total);
    let html = '<div class="text-sm text-gray-400">Menampilkan ' + start + ' - ' + end + ' dari ' + pagination.total + '</div>';
    html += '<div class="flex space-x-2">';
    if (pagination.current_page > 1) {
        html += '<button onclick="changePage(' + (pagination.current_page - 1) + ')" class="px-3 py-1 bg-white/10 rounded-xl hover:bg-white/20 transition">Prev</button>';
    }
    if (pagination.current_page < pagination.last_page) {
        html += '<button onclick="changePage(' + (pagination.current_page + 1) + ')" class="px-3 py-1 bg-white/10 rounded-xl hover:bg-white/20 transition">Next</button>';
    }
    html += '</div>';
    container.innerHTML = html;
}

// ==================== CHANGE PAGE ====================
function changePage(page) {
    currentPage = page;
    loadPejantanData();
}

// ==================== LOAD DETAIL PEJANTAN (VERSI FINAL DENGAN GRAFIK TETAP MUNCUL) ====================
async function loadDetailPejantan(livestockId) {
    if (!livestockId) return;

    try {
        const response = await TernakPark.api.fetchData('/web-api/livestocks/' + livestockId + '/detail');
        if (response.success) {
            const data = response.data;

            const detailHtml = `
                <div><span class="text-gray-400">Tagging:</span> ${escapeHtml(data.ear_tag)}</div>
                <div><span class="text-gray-400">Jenis Domba:</span> ${escapeHtml(data.breed_type)}</div>
                <div><span class="text-gray-400">BB Terbaru:</span> ${data.current_weight} kg</div>
                <div><span class="text-gray-400">Umur:</span> ${data.age_days} hari</div>
                <div><span class="text-gray-400">ADG:</span> ${data.average_daily_gain ? data.average_daily_gain.toFixed(3) : '-'} kg/hari</div>
                <div><span class="text-gray-400">Tanggal Lahir:</span> ${data.birth_date || '-'}</div>
                <div><span class="text-gray-400">Kandang:</span> ${escapeHtml(data.pen?.name || '-')}</div>
                <div><span class="text-gray-400">Status:</span> ${data.status ? 'Aktif' : 'Tidak Aktif'}</div>
                <div><span class="text-gray-400">Kondisi:</span> ${escapeHtml(data.condition || '-')}</div>
                <div><span class="text-gray-400">Kesehatan:</span> ${escapeHtml(data.health_status || '-')}</div>
                <div><span class="text-gray-400">Catatan:</span> ${escapeHtml(data.notes || '-')}</div>
            `;
            const detailContainer = document.getElementById('detailPejantanInfo');
            if (detailContainer) detailContainer.innerHTML = detailHtml;

            const modal = document.getElementById('modalDetailPejantan');
            modal.classList.remove('hidden');

            const renderChartAfterModalReady = function() {
                const canvas = document.getElementById('weightHistoryChart');
                if (!canvas) {
                    console.warn('Canvas weightHistoryChart tidak ditemukan');
                    return;
                }
                const ctx = canvas.getContext('2d');
                if (weightHistoryChart) {
                    weightHistoryChart.destroy();
                    weightHistoryChart = null;
                }

                const weightRecords = data.weight_records || [];
                let labels = [];
                let values = [];

                if (weightRecords.length > 0) {
                    const sorted = [...weightRecords].sort(function(a, b) {
                        return new Date(a.record_date) - new Date(b.record_date);
                    });
                    labels = sorted.map(function(w) {
                        return new Date(w.record_date).toLocaleDateString('id-ID');
                    });
                    values = sorted.map(function(w) {
                        return w.weight_kg;
                    });
                }
                else if (data.initial_weight !== undefined && data.initial_weight !== null && data.current_weight !== undefined) {
                    labels = ['Berat Awal', 'Berat Terbaru'];
                    values = [parseFloat(data.initial_weight), parseFloat(data.current_weight)];
                    if (values[0] === values[1]) {
                        labels = ['Berat Saat Ini'];
                        values = [values[0]];
                    }
                }
                else if (data.current_weight !== undefined) {
                    labels = ['Berat Saat Ini'];
                    values = [parseFloat(data.current_weight)];
                }
                else {
                    labels = ['Belum ada data berat'];
                    values = [0];
                }

                weightHistoryChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Berat (kg)',
                            data: values,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 4,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: { beginAtZero: true, title: { display: true, text: 'kg', color: '#cbd5e1' } },
                            x: { ticks: { color: '#e2e8f0', maxRotation: 45, minRotation: 45 } }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Berat: ' + context.raw.toFixed(2) + ' kg';
                                    }
                                }
                            }
                        }
                    }
                });
            };

            requestAnimationFrame(function() {
                setTimeout(renderChartAfterModalReady, 200);
            });

            const riwayatKawin = data.mating_records || [];
            let kawinHtml = '';
            if (riwayatKawin.length === 0) {
                kawinHtml = '<tr><td colspan="4" class="text-center py-2 text-gray-400">Tidak ada data perkawinan<\/td><\/tr>';
            } else {
                for (const rec of riwayatKawin) {
                    kawinHtml += `
                        <tr class="border-b border-white/10">
                            <td class="px-3 py-2">${rec.mating_date || '-'}<\/td>
                            <td class="px-3 py-2">${escapeHtml(rec.female_ear_tag || '-')}<\/td>
                            <td class="px-3 py-2">${escapeHtml(rec.pen_name || '-')}<\/td>
                            <td class="px-3 py-2"><span class="px-2 py-1 rounded-full text-xs ${rec.success ? 'bg-emerald-500/20 text-emerald-200' : 'bg-yellow-500/20 text-yellow-200'}">${rec.success ? 'Berhasil' : 'Proses'}<\/span><\/td>
                        <\/tr>
                    `;
                }
            }
            const riwayatTable = document.getElementById('riwayatKawinTable');
            if (riwayatTable) riwayatTable.innerHTML = kawinHtml;
        } else {
            if (typeof TernakPark !== 'undefined' && TernakPark.ui) {
                TernakPark.ui.showToast('Gagal memuat detail pejantan', 'error');
            }
        }
    } catch (error) {
        console.error('Error loading detail pejantan:', error);
        if (typeof TernakPark !== 'undefined' && TernakPark.ui) {
            TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
        }
    }
}

// ==================== UTILITIES ====================
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