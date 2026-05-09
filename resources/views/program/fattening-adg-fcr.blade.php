@extends('layouts.app')

@section('title', 'ADG & FCR - Fattening')
@section('header-title', 'ADG & FCR Analysis')

@section('content')
<div class="space-y-6">
    <div class="flex justify-end">
        <a href="{{ route('program.fattening') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Fattening
        </a>
    </div>

    <div class="glass-card p-6">
        <!-- Ringkasan Statistik -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white/5 p-3 rounded-xl text-center">
                <p class="text-gray-400">ADG (kg/hari)</p>
                <p class="text-2xl font-bold text-emerald-300" id="summaryAdg">-</p>
            </div>
            <div class="bg-white/5 p-3 rounded-xl text-center">
                <p class="text-gray-400">Upweight (kg)</p>
                <p class="text-2xl font-bold text-emerald-300" id="summaryUpweight">-</p>
            </div>
            <div class="bg-white/5 p-3 rounded-xl text-center">
                <p class="text-gray-400">QTY ternak</p>
                <p class="text-2xl font-bold text-emerald-300" id="summaryQty">-</p>
            </div>
            <div class="bg-white/5 p-3 rounded-xl text-center">
                <p class="text-gray-400">FCR</p>
                <p class="text-2xl font-bold text-emerald-300" id="summaryFcr">-</p>
            </div>
        </div>

        <!-- Grafik ADG per bulan -->
        <div class="mb-6">
            <h4 class="font-semibold text-white mb-2">📈 Average ADG per bulan</h4>
            <canvas id="adgPerBulanChart" height="250" class="w-full bg-white/5 rounded-xl p-2"></canvas>
            <div id="noAdgDataMessage" class="text-center text-gray-400 hidden mt-2">
                <i class="fas fa-chart-line text-2xl mb-1"></i><br>
                Belum ada data ADG untuk ditampilkan.
            </div>
        </div>

        <!-- Filter Tanggal dan Tabel -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-4 items-end mb-3">
                <div>
                    <label class="text-gray-300 text-sm">Tanggal Awal</label>
                    <input type="date" id="startDate" class="bg-white/10 border border-white/20 rounded-xl px-3 py-2 text-white">
                </div>
                <div>
                    <label class="text-gray-300 text-sm">Tanggal Akhir</label>
                    <input type="date" id="endDate" class="bg-white/10 border border-white/20 rounded-xl px-3 py-2 text-white">
                </div>
                <button id="filterDateBtn" class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-4 py-2 rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="custom-table w-full text-sm">
                    <thead class="bg-white/5">
                        <tr class="text-gray-300 text-xs uppercase">
                            <th class="px-3 py-2">Bulan</th>
                            <th class="px-3 py-2">TAGGING</th>
                            <th class="px-3 py-2">BB (kg)</th>
                            <th class="px-3 py-2">Upweight (kg)</th>
                            <th class="px-3 py-2">ADG (kg/hari)</th>
                         </tr>
                    </thead>
                    <tbody id="tabelDataTernak">
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-400">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Qty Pakan dan Total Upweight -->
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white/5 p-4 rounded-xl text-center">
                <p class="text-gray-300">🌾 Qty pakan (kg)</p>
                <p class="text-3xl font-bold text-emerald-300" id="qtyPakan">-</p>
            </div>
            <div class="bg-white/5 p-4 rounded-xl text-center">
                <p class="text-gray-300">📈 Total upweight (kg)</p>
                <p class="text-3xl font-bold text-emerald-300" id="totalUpweight">-</p>
            </div>
        </div>

        <!-- Jenis Pakan yang Digunakan -->
        <div class="mb-6">
            <h4 class="font-semibold text-white mb-2">🥕 Jenis pakan yang digunakan</h4>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <canvas id="pieChartPakan" height="200" class="w-full bg-white/5 rounded-xl p-2"></canvas>
                    <div id="noPakanDataMessage" class="text-center text-gray-400 hidden mt-2">
                        <i class="fas fa-chart-pie text-2xl mb-1"></i><br>
                        Belum ada data penggunaan pakan.
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="custom-table w-full text-sm">
                        <thead class="bg-white/5">
                            <tr class="text-gray-300 text-xs uppercase">
                                <th class="px-3 py-2">JENIS PAKAN</th>
                                <th class="px-3 py-2">KELUAR (Kg)</th>
                                <th class="px-3 py-2">Persentase</th>
                             </tr>
                        </thead>
                        <tbody id="tabelPakan"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- FCR per bulan -->
        <div>
            <h4 class="font-semibold text-white mb-2">📉 FCR per bulan</h4>
            <canvas id="fcrPerBulanChart" height="250" class="w-full bg-white/5 rounded-xl p-2"></canvas>
            <div id="noFcrDataMessage" class="text-center text-gray-400 hidden mt-2">
                <i class="fas fa-chart-line text-2xl mb-1"></i><br>
                Belum ada data FCR yang tersedia.
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let adgPerBulanChart = null;
    let pieChartPakan = null;
    let fcrPerBulanChart = null;

    document.addEventListener('DOMContentLoaded', function() {
        loadAdgFcrData();

        const filterButton = document.getElementById('filterDateBtn');
        if (filterButton) {
            filterButton.addEventListener('click', function() {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                if (startDate && endDate) {
                    loadAdgFcrData(startDate, endDate);
                } else {
                    TernakPark.ui.showToast('Pilih kedua tanggal filter', 'warning');
                }
            });
        }
    });

    async function loadAdgFcrData(startDate = '', endDate = '') {
        let url = '/web-api/program/fattening-adg-fcr';
        if (startDate && endDate) {
            url += '?start_date=' + encodeURIComponent(startDate) + '&end_date=' + encodeURIComponent(endDate);
        }

        try {
            const response = await TernakPark.api.fetchData(url);
            if (response.success && response.data) {
                const data = response.data;

                // Update ringkasan
                document.getElementById('summaryAdg').innerText = (data.avg_adg !== undefined && data.avg_adg !== null ? data.avg_adg : 0).toFixed(3);
                document.getElementById('summaryUpweight').innerText = (data.total_upweight || 0).toLocaleString('id-ID');
                document.getElementById('summaryQty').innerText = (data.qty_ternak || 0).toLocaleString('id-ID');
                document.getElementById('summaryFcr').innerText = (data.fcr || 0).toFixed(2);
                document.getElementById('qtyPakan').innerText = (data.qty_pakan || 0).toLocaleString('id-ID');
                document.getElementById('totalUpweight').innerText = (data.total_upweight || 0).toLocaleString('id-ID');

                // Grafik ADG per bulan
                renderAdgChart(data.adg_bulan_labels || [], data.adg_bulan_values || []);

                // Tabel data ternak
                renderDataTernakTable(data.data_ternak || []);

                // Pie chart dan tabel pakan
                renderPakanCharts(data.pakan_labels || [], data.pakan_values || [], data.pakan_rincian || []);

                // Grafik FCR per bulan
                renderFcrChart(data.fcr_bulan_labels || [], data.fcr_bulan_values || []);

            } else {
                TernakPark.ui.showToast(response.message || 'Gagal memuat data', 'error');
                renderAdgChart([], []);
                renderPakanCharts([], [], []);
                renderFcrChart([], []);
            }
        } catch (error) {
            console.error(error);
            TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
            renderAdgChart([], []);
            renderPakanCharts([], [], []);
            renderFcrChart([], []);
        }
    }

    function renderAdgChart(labels, values) {
        const canvas = document.getElementById('adgPerBulanChart');
        const noDataMessage = document.getElementById('noAdgDataMessage');
        if (adgPerBulanChart) {
            adgPerBulanChart.destroy();
        }

        if (!labels || labels.length === 0 || !values || values.length === 0) {
            canvas.style.display = 'none';
            if (noDataMessage) noDataMessage.classList.remove('hidden');
            return;
        }

        canvas.style.display = 'block';
        if (noDataMessage) noDataMessage.classList.add('hidden');

        adgPerBulanChart = new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'ADG (kg/hari)',
                    data: values,
                    backgroundColor: '#f59e0b',
                    borderRadius: 8,
                    barPercentage: 0.7,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { labels: { color: '#e2e8f0' } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'ADG: ' + context.raw.toFixed(3) + ' kg/hari';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'kg/hari', color: '#cbd5e1' },
                        ticks: { color: '#e2e8f0' },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    },
                    x: {
                        ticks: { color: '#e2e8f0', maxRotation: 35, minRotation: 35 },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    }
                }
            }
        });
    }

    function renderDataTernakTable(data) {
        const tableBody = document.getElementById('tabelDataTernak');
        if (!data || data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-400">Tidak ada data ternak</td></tr>';
            return;
        }

        let html = '';
        for (let i = 0; i < data.length; i++) {
            const row = data[i];
            html += '<tr class="border-b border-white/10 hover:bg-white/5 transition">';
            html += '<td class="px-3 py-2">' + escapeHtml(row.bulan || '-') + '</td>';
            html += '<td class="px-3 py-2 font-medium">' + escapeHtml(row.ear_tag || '-') + '</td>';
            html += '<td class="px-3 py-2">' + (row.bb ? row.bb.toFixed(2) : '-') + '</td>';
            html += '<td class="px-3 py-2">' + (row.upweight ? row.upweight.toFixed(2) : '-') + '</td>';
            html += '<td class="px-3 py-2">' + (row.adg ? row.adg.toFixed(3) : '-') + '</td>';
            html += '</tr>';
        }
        tableBody.innerHTML = html;
    }

    function renderPakanCharts(labels, values, rincian) {
        const canvas = document.getElementById('pieChartPakan');
        const noDataMessage = document.getElementById('noPakanDataMessage');
        const tableBody = document.getElementById('tabelPakan');

        if (pieChartPakan) {
            pieChartPakan.destroy();
        }

        if (!labels || labels.length === 0) {
            canvas.style.display = 'none';
            if (noDataMessage) noDataMessage.classList.remove('hidden');
            tableBody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-gray-400">Tidak ada data pakan</td></tr>';
            return;
        }

        canvas.style.display = 'block';
        if (noDataMessage) noDataMessage.classList.add('hidden');

        const backgroundColors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316', '#6b7280', '#a855f7'];

        pieChartPakan = new Chart(canvas.getContext('2d'), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: backgroundColors.slice(0, labels.length),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'right', labels: { color: '#e2e8f0' } },
                    tooltip: { callbacks: { label: function(context) { return context.label + ': ' + context.raw.toFixed(2) + ' kg'; } } }
                }
            }
        });

        // Tabel rincian pakan
        if (rincian && rincian.length > 0) {
            let html = '';
            for (let i = 0; i < rincian.length; i++) {
                const item = rincian[i];
                html += '<tr class="border-b border-white/10">';
                html += '<td class="px-3 py-2">' + escapeHtml(item.nama_pakan || '-') + '</td>';
                html += '<td class="px-3 py-2">' + (item.keluar_kg ? item.keluar_kg.toFixed(2) : '-') + '</td>';
                html += '<td class="px-3 py-2">' + (item.persentase ? item.persentase.toFixed(1) + '%' : '-') + '</td>';
                html += '</tr>';
            }
            tableBody.innerHTML = html;
        } else {
            tableBody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-gray-400">Tidak ada data rincian</td></tr>';
        }
    }

    function renderFcrChart(labels, values) {
        const canvas = document.getElementById('fcrPerBulanChart');
        const noDataMessage = document.getElementById('noFcrDataMessage');
        if (fcrPerBulanChart) {
            fcrPerBulanChart.destroy();
        }

        if (!labels || labels.length === 0 || !values || values.length === 0) {
            canvas.style.display = 'none';
            if (noDataMessage) noDataMessage.classList.remove('hidden');
            return;
        }

        canvas.style.display = 'block';
        if (noDataMessage) noDataMessage.classList.add('hidden');

        fcrPerBulanChart = new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'FCR',
                    data: values,
                    backgroundColor: '#ef4444',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { labels: { color: '#e2e8f0' } },
                    tooltip: { callbacks: { label: function(context) { return 'FCR: ' + context.raw.toFixed(2); } } }
                },
                scales: {
                    y: {
                        title: { display: true, text: 'Nilai FCR', color: '#cbd5e1' },
                        ticks: { color: '#e2e8f0' },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    },
                    x: {
                        ticks: { color: '#e2e8f0' },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    }
                }
            }
        });
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