@extends('layouts.app')

@section('title', 'Breeding - Anakan')
@section('header-title', 'Manajemen Anakan')

@section('content')
<div class="space-y-6">
    {{-- Tombol Kembali --}}
    <div class="flex justify-end">
        <a href="{{ route('program.breeding') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Breeding
        </a>
    </div>

    {{-- Kartu Statistik Anakan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div><p class="text-gray-300 text-sm">Total Anakan</p><p class="stat-value" id="total-anak">-</p></div>
                <div class="bg-emerald-500/20 p-3 rounded-2xl"><i class="fas fa-baby text-emerald-300 text-xl"></i></div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div><p class="text-gray-300 text-sm">Anakan Jantan</p><p class="stat-value" id="jantan-count">-</p></div>
                <div class="bg-blue-500/20 p-3 rounded-2xl"><i class="fas fa-mars text-blue-300 text-xl"></i></div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div><p class="text-gray-300 text-sm">Anakan Betina</p><p class="stat-value" id="betina-count">-</p></div>
                <div class="bg-pink-500/20 p-3 rounded-2xl"><i class="fas fa-venus text-pink-300 text-xl"></i></div>
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
                <div><p class="text-gray-300 text-sm">Lepas Sapih</p><p class="stat-value" id="lepas-sapih">-</p></div>
                <div class="bg-emerald-500/20 p-3 rounded-2xl"><i class="fas fa-check-circle text-emerald-300 text-xl"></i></div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div><p class="text-gray-300 text-sm">Belum Lepas Sapih</p><p class="stat-value" id="belum-lepas">-</p></div>
                <div class="bg-yellow-500/20 p-3 rounded-2xl"><i class="fas fa-clock text-yellow-300 text-xl"></i></div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div><p class="text-gray-300 text-sm">Anakan di Kandang Khusus</p><p class="stat-value" id="kandang-khusus">-</p></div>
                <div class="bg-indigo-500/20 p-3 rounded-2xl"><i class="fas fa-warehouse text-indigo-300 text-xl"></i></div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div><p class="text-gray-300 text-sm">Mortalitas</p><p class="stat-value" id="mortalitas">-</p></div>
                <div class="bg-red-500/20 p-3 rounded-2xl"><i class="fas fa-skull-crossbones text-red-300 text-xl"></i></div>
            </div>
        </div>
    </div>

    {{-- Grafik Distribusi Anakan --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-card p-5">
            <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
                <i class="fas fa-chart-pie text-emerald-400"></i>
                <h3 class="font-bold text-white text-lg">Distribusi Jenis Kelamin</h3>
            </div>
            <canvas id="genderChart" height="250" class="w-full" style="max-height: 250px;"></canvas>
        </div>
        <div class="glass-card p-5">
            <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
                <i class="fas fa-chart-line text-emerald-400"></i>
                <h3 class="font-bold text-white text-lg">Distribusi Umur (hari)</h3>
            </div>
            <canvas id="ageChart" height="250" class="w-full" style="max-height: 250px;"></canvas>
        </div>
    </div>

    {{-- Filter Data Anakan --}}
    <div class="glass-card p-5">
        <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
            <i class="fas fa-filter text-emerald-400"></i>
            <h3 class="font-bold text-white text-lg">Filter Data Anakan</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="text-gray-300 text-sm">Jenis Kelamin</label>
                <select id="filterGender" class="w-full bg-white/10 border border-white/20 rounded-xl px-3 py-2 text-white focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua</option>
                    <option value="male">Jantan</option>
                    <option value="female">Betina</option>
                </select>
            </div>
            <div>
                <label class="text-gray-300 text-sm">Status Sapih</label>
                <select id="filterWeaned" class="w-full bg-white/10 border border-white/20 rounded-xl px-3 py-2 text-white focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua</option>
                    <option value="1">Sudah Lepas Sapih</option>
                    <option value="0">Belum Lepas Sapih</option>
                </select>
            </div>
            <div>
                <label class="text-gray-300 text-sm">Induk (Ear Tag)</label>
                <select id="filterInduk" class="w-full bg-white/10 border border-white/20 rounded-xl px-3 py-2 text-white focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua Induk</option>
                </select>
            </div>
            <div class="flex items-end">
                <button id="btnApplyFilter" class="w-full bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-4 py-2 rounded-xl shadow-md transition transform hover:scale-[1.02]">
                    <i class="fas fa-search mr-1"></i> Terapkan Filter
                </button>
            </div>
        </div>
    </div>

    {{-- Tabel Data Anakan --}}
    <div class="glass-card p-0 overflow-hidden">
        <div class="flex items-center gap-2 border-b border-white/10 p-5">
            <i class="fas fa-table-list text-emerald-400"></i>
            <h3 class="font-bold text-white text-lg">Data Anakan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="custom-table w-full text-sm">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr class="text-gray-300 text-xs uppercase">
                        <th class="px-3 py-2 text-left">Tagging</th>
                        <th class="px-3 py-2 text-left">Jenis Domba</th>
                        <th class="px-3 py-2 text-left">Kelamin</th>
                        <th class="px-3 py-2 text-left">BB (kg)</th>
                        <th class="px-3 py-2 text-left">Umur (hari)</th>
                        <th class="px-3 py-2 text-left">Induk Betina</th>
                        <th class="px-3 py-2 text-left">Induk Jantan</th>
                        <th class="px-3 py-2 text-left">Status Sapih</th>
                        <th class="px-3 py-2 text-left">Kandang</th>
                        <th class="px-3 py-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="anakan-table-body">
                    <tr>
                        <td colspan="10" class="text-center py-8 text-gray-400">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="pagination" class="p-5 border-t border-white/10 flex justify-between items-center"></div>
    </div>
</div>

{{-- Modal Detail Anakan --}}
<div id="modalDetailAnakan" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"></div>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-black/40 backdrop-blur-md rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-white/20">
            <div class="px-6 pt-6 pb-4 sm:px-8 sm:pt-8">
                <div class="flex justify-between items-center border-b border-white/10 pb-3 mb-4">
                    <h3 class="text-xl font-bold text-white">📋 Detail Anakan</h3>
                    <button class="close-modal text-gray-400 hover:text-white text-2xl leading-none">&times;</button>
                </div>
                <div id="detailAnakanInfo" class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm bg-white/5 rounded-xl p-4 border border-white/10"></div>
                <div class="mt-6">
                    <h4 class="font-semibold text-white mb-2">📈 Riwayat Berat Badan</h4>
                    <canvas id="weightHistoryChart" height="200" class="w-full bg-white/5 rounded-xl p-2"></canvas>
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
let genderChart = null;
let ageChart = null;
let weightHistoryChart = null;
let currentPage = 1;
let filters = {};

// ==================== DOM READY ====================
document.addEventListener('DOMContentLoaded', function() {
    loadAnakanData();
    loadIndukOptions();

    const applyFilterBtn = document.getElementById('btnApplyFilter');
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function() {
            filters = {
                gender: document.getElementById('filterGender').value,
                weaned: document.getElementById('filterWeaned').value,
                induk: document.getElementById('filterInduk').value
            };
            currentPage = 1;
            loadAnakanData();
        });
    }

    const closeModalButtons = document.querySelectorAll('#modalDetailAnakan .close-modal');
    closeModalButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const modal = document.getElementById('modalDetailAnakan');
            modal.classList.add('hidden');
            if (weightHistoryChart) {
                weightHistoryChart.destroy();
                weightHistoryChart = null;
            }
        });
    });
});

// ==================== LOAD INDUK OPTIONS ====================
async function loadIndukOptions() {
    try {
        const response = await TernakPark.api.fetchData('/web-api/livestocks/data?per_page=1000&gender=female&status=1');
        const select = document.getElementById('filterInduk');
        if (select) {
            select.innerHTML = '<option value="">Semua Induk</option>';
            if (response.success && response.data.livestocks) {
                response.data.livestocks.forEach(function(induk) {
                    select.innerHTML += '<option value="' + escapeHtml(induk.ear_tag) + '">' + escapeHtml(induk.ear_tag) + ' - ' + escapeHtml(induk.breed_type) + '</option>';
                });
            }
        }
    } catch(error) {
        console.error('Error loading induk options:', error);
        if (typeof TernakPark !== 'undefined' && TernakPark.ui) {
            TernakPark.ui.showToast('Gagal memuat daftar induk', 'error');
        }
    }
}

// ==================== LOAD ANAKAN DATA ====================
async function loadAnakanData() {
    try {
        let url = '/web-api/program/breeding/anakan?page=' + currentPage;
        if (filters.gender) url += '&gender=' + encodeURIComponent(filters.gender);
        if (filters.weaned !== undefined && filters.weaned !== '') {
            url += '&weaned=' + encodeURIComponent(filters.weaned);
        }
        if (filters.induk) url += '&induk=' + encodeURIComponent(filters.induk);

        const response = await TernakPark.api.fetchData(url);
        if (response.success) {
            const data = response.data;

            document.getElementById('total-anak').innerText = data.total_anak || 0;
            document.getElementById('jantan-count').innerText = data.jantan_count || 0;
            document.getElementById('betina-count').innerText = data.betina_count || 0;
            document.getElementById('avg-bb').innerText = (data.avg_bb || 0).toFixed(2);
            document.getElementById('lepas-sapih').innerText = data.lepas_sapih || 0;
            document.getElementById('belum-lepas').innerText = data.belum_lepas || 0;
            document.getElementById('kandang-khusus').innerText = data.kandang_khusus || 0;
            document.getElementById('mortalitas').innerText = data.mortalitas || 0;

            if (genderChart) genderChart.destroy();
            const genderCanvas = document.getElementById('genderChart');
            if (genderCanvas) {
                genderChart = new Chart(genderCanvas, {
                    type: 'pie',
                    data: {
                        labels: ['Jantan', 'Betina'],
                        datasets: [{ data: [data.jantan_count || 0, data.betina_count || 0], backgroundColor: ['#3b82f6', '#ec4899'] }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { position: 'bottom', labels: { color: '#e2e8f0' } } }
                    }
                });
            }

            if (ageChart) ageChart.destroy();
            const ageCanvas = document.getElementById('ageChart');
            if (ageCanvas) {
                ageChart = new Chart(ageCanvas, {
                    type: 'bar',
                    data: {
                        labels: data.age_labels || ['0-30','31-60','61-90','91-120','>120'],
                        datasets: [{
                            label: 'Jumlah Anakan',
                            data: data.age_distribution || [0,0,0,0,0],
                            backgroundColor: '#f59e0b',
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { labels: { color: '#e2e8f0' } } },
                        scales: {
                            x: { ticks: { color: '#e2e8f0' }, grid: { display: false } },
                            y: { ticks: { color: '#e2e8f0' }, grid: { color: 'rgba(255,255,255,0.05)' } }
                        }
                    }
                });
            }

            renderAnakanTable(data.anakan);
            renderPagination(data.pagination);
        } else {
            if (typeof TernakPark !== 'undefined' && TernakPark.ui) {
                TernakPark.ui.showToast(response.message || 'Gagal memuat data anakan', 'error');
            }
        }
    } catch(error) {
        console.error('Error loading anakan data:', error);
        if (typeof TernakPark !== 'undefined' && TernakPark.ui) {
            TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
        }
    }
}

// ==================== RENDER TABEL ANAKAN ====================
function renderAnakanTable(anakan) {
    const tbody = document.getElementById('anakan-table-body');
    if (!tbody) return;

    if (!anakan || anakan.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" class="text-center py-8 text-gray-400">Tidak ada data<\/td><\/tr>';
        return;
    }

    let html = '';
    for (let i = 0; i < anakan.length; i++) {
        const item = anakan[i];
        html += '<tr class="border-b border-white/10 hover:bg-white/5 transition">';
        html += '<td class="px-3 py-2 font-medium">' + escapeHtml(item.ear_tag) + '<\/td>';
        html += '<td class="px-3 py-2">' + escapeHtml(item.breed_type) + '<\/td>';
        html += '<td class="px-3 py-2">' + (item.gender === 'male' ? 'Jantan' : 'Betina') + '<\/td>';
        html += '<td class="px-3 py-2">' + (item.current_weight || '-') + '<\/td>';
        html += '<td class="px-3 py-2">' + (item.age_days || '-') + ' hari<\/td>';
        html += '<td class="px-3 py-2">' + escapeHtml(item.mother_ear_tag || '-') + '<\/td>';
        html += '<td class="px-3 py-2">' + escapeHtml(item.father_ear_tag || '-') + '<\/td>';
        html += '<td class="px-3 py-2"><span class="px-2 py-1 rounded-full text-xs ' + (item.is_weaned ? 'bg-emerald-500/20 text-emerald-200' : 'bg-yellow-500/20 text-yellow-200') + '">' + (item.is_weaned ? 'Lepas Sapih' : 'Belum Lepas Sapih') + '<\/span><\/td>';
        html += '<td class="px-3 py-2">' + escapeHtml(item.pen_name || '-') + '<\/td>';
        html += '<td class="px-3 py-2 text-right"><button class="btn-detail text-emerald-400 hover:text-emerald-300" data-id="' + item.id + '"><i class="fas fa-eye"></i> Detail<\/button><\/td>';
        html += '<\/tr>';
    }
    tbody.innerHTML = html;

    const detailButtons = document.querySelectorAll('.btn-detail');
    detailButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const livestockId = this.getAttribute('data-id');
            if (livestockId) {
                loadDetailAnakan(livestockId);
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
    loadAnakanData();
}

// ==================== LOAD DETAIL ANAKAN (DENGAN GRAFIK TETAP MUNCUL) ====================
async function loadDetailAnakan(livestockId) {
    if (!livestockId) return;

    try {
        const response = await TernakPark.api.fetchData('/web-api/livestocks/' + livestockId + '/detail');
        if (response.success) {
            const data = response.data;

            const detailHtml = `
                <div><span class="text-gray-400">Tagging:</span> ${escapeHtml(data.ear_tag)}</div>
                <div><span class="text-gray-400">Jenis Domba:</span> ${escapeHtml(data.breed_type)}</div>
                <div><span class="text-gray-400">Jenis Kelamin:</span> ${data.gender === 'male' ? 'Jantan' : 'Betina'}</div>
                <div><span class="text-gray-400">BB Terbaru:</span> ${data.current_weight} kg</div>
                <div><span class="text-gray-400">Umur:</span> ${data.age_days} hari</div>
                <div><span class="text-gray-400">Tanggal Lahir:</span> ${data.birth_date || '-'}</div>
                <div><span class="text-gray-400">Induk Jantan:</span> ${escapeHtml(data.father_ear_tag || '-')}</div>
                <div><span class="text-gray-400">Induk Betina:</span> ${escapeHtml(data.mother_ear_tag || '-')}</div>
                <div><span class="text-gray-400">Kandang:</span> ${escapeHtml(data.pen?.name || '-')}</div>
                <div><span class="text-gray-400">Status:</span> ${data.status ? 'Aktif' : 'Tidak Aktif'}</div>
                <div><span class="text-gray-400">Kondisi:</span> ${escapeHtml(data.condition || '-')}</div>
                <div><span class="text-gray-400">Catatan:</span> ${escapeHtml(data.notes || '-')}</div>
            `;
            const detailContainer = document.getElementById('detailAnakanInfo');
            if (detailContainer) detailContainer.innerHTML = detailHtml;

            const modal = document.getElementById('modalDetailAnakan');
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
        } else {
            if (typeof TernakPark !== 'undefined' && TernakPark.ui) {
                TernakPark.ui.showToast('Gagal memuat detail anakan', 'error');
            }
        }
    } catch(error) {
        console.error('Error loading detail anakan:', error);
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