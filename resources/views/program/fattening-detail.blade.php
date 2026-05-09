@extends('layouts.app')

@section('title', 'Detail per Ternak - Fattening')
@section('header-title', 'Detail per Ternak (Fattening)')

@section('content')
<div class="space-y-6">
    <div class="flex justify-end">
        <a href="{{ route('program.fattening') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Fattening
        </a>
    </div>

    <div class="glass-card p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 text-sm mb-1">Pilih Ear Tag</label>
                <select id="tagSelect" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white">
                    <option value="">-- Pilih Ternak --</option>
                </select>
            </div>
            <div class="flex justify-end items-end">
                <button id="btnCatatBerat" class="hidden bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl transition flex items-center gap-2 shadow-md">
                    <i class="fas fa-weight"></i> Catat Berat Sekarang
                </button>
            </div>
        </div>

        <div id="detailInfo" class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-3 text-sm bg-white/5 rounded-xl p-4 border border-white/10">
            <!-- Akan diisi JavaScript -->
        </div>

        <div class="mt-6">
            <h4 class="font-semibold text-white mb-2">📊 Upweight Ternak 6 Bulan Terakhir (kg)</h4>
            <canvas id="upweightChart" height="250" class="w-full bg-white/5 rounded-xl p-2"></canvas>
            <div id="noWeightMessage" class="text-center text-gray-400 hidden mt-2">
                <i class="fas fa-chart-line text-2xl mb-1"></i><br>
                Belum ada data timbangan untuk ternak ini.<br>
                <span class="text-xs">Klik tombol "Catat Berat Sekarang" untuk menambahkan berat badan.</span>
            </div>
        </div>

        <div class="mt-6">
            <h4 class="font-semibold text-white mb-2">📋 Riwayat Berat Badan (Data Real)</h4>
            <div class="overflow-x-auto">
                <table class="custom-table w-full text-sm">
                    <thead class="bg-white/5">
                        <tr class="text-gray-300">
                            <th class="px-3 py-2">Tanggal Timbang</th>
                            <th class="px-3 py-2">Berat (kg)</th>
                        </table>
                    </thead>
                    <tbody id="weightHistoryTable">
                        <tr><td colspan="2" class="text-center py-4 text-gray-400">Pilih ternak terlebih dahulu<\/td><\/tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ==================== VARIABEL GLOBAL ====================
    let weightChart = null;
    let currentLivestockId = null;

    // ==================== UTILITY: ESCAPE HTML ====================
    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>]/g, function(match) {
            if (match === '&') return '&amp;';
            if (match === '<') return '&lt;';
            if (match === '>') return '&gt;';
            return match;
        });
    }

    // ==================== LOAD DAFTAR TERNAK ====================
    async function loadLivestockList() {
        try {
            const response = await TernakPark.api.fetchData('/web-api/livestocks/data?per_page=1000');
            if (response.success && response.data.livestocks) {
                const select = document.getElementById('tagSelect');
                let options = '<option value="">-- Pilih Ternak --</option>';
                response.data.livestocks.forEach(function(livestock) {
                    options += `<option value="${livestock.id}">${escapeHtml(livestock.ear_tag)} - ${escapeHtml(livestock.breed_type)}</option>`;
                });
                select.innerHTML = options;
            } else {
                TernakPark.ui.showToast('Gagal memuat daftar ternak', 'error');
            }
        } catch (error) {
            console.error(error);
            TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
        }
    }

    // ==================== LOAD DETAIL TERNAK ====================
    async function loadLivestockDetail(livestockId) {
        try {
            const response = await TernakPark.api.fetchData(`/web-api/livestocks/${livestockId}/detail`);
            if (response.success) {
                const data = response.data;
                renderDetailInfo(data);
                renderWeightHistory(data.weight_records || []);
                renderWeightChart(data.weight_records || []);
            } else {
                TernakPark.ui.showToast('Gagal memuat detail ternak', 'error');
            }
        } catch (error) {
            console.error(error);
            TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
        }
    }

    // ==================== RENDER INFORMASI DETAIL ====================
    function renderDetailInfo(livestock) {
        const html = `
            <div><span class="text-gray-400">Ear Tag:</span> ${escapeHtml(livestock.ear_tag)}</div>
            <div><span class="text-gray-400">Kandang:</span> ${escapeHtml(livestock.pen?.name || '-')}</div>
            <div><span class="text-gray-400">Kondisi:</span> ${escapeHtml(livestock.condition || '-')}</div>
            <div><span class="text-gray-400">Jenis Kelamin:</span> ${livestock.gender === 'male' ? 'Jantan' : 'Betina'}</div>
            <div><span class="text-gray-400">BB Awal (kg):</span> ${livestock.initial_weight}</div>
            <div><span class="text-gray-400">BB Terbaru (kg):</span> ${livestock.current_weight}</div>
            <div><span class="text-gray-400">ADG (kg/hari):</span> ${livestock.average_daily_gain?.toFixed(3) ?? '-'}</div>
            <div><span class="text-gray-400">Day On Farm:</span> ${livestock.day_on_farm || 0} hari</div>
            <div><span class="text-gray-400">Umur:</span> ${livestock.age_days} hari</div>
            <div><span class="text-gray-400">Jenis Domba:</span> ${escapeHtml(livestock.breed_type?.replace(/_/g, ' ') || '-')}</div>
        `;
        document.getElementById('detailInfo').innerHTML = html;
    }

    // ==================== RENDER TABEL RIWAYAT BERAT ====================
    function renderWeightHistory(weightRecords) {
        const tbody = document.getElementById('weightHistoryTable');
        if (!weightRecords || weightRecords.length === 0) {
            tbody.innerHTML = `<tr><td colspan="2" class="text-center py-4 text-gray-400">Belum ada data timbangan. Catat berat sekarang!<\/td><\/tr>`;
            return;
        }

        // Urutkan dari terbaru ke terlama
        const sorted = [...weightRecords].sort(function(a, b) {
            return new Date(b.record_date) - new Date(a.record_date);
        });

        let html = '';
        sorted.forEach(function(record) {
            const date = new Date(record.record_date).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            html += `<tr class="border-b border-white/10">
                        <td class="px-3 py-2">${escapeHtml(date)}<\/td>
                        <td class="px-3 py-2 font-semibold text-emerald-300">${record.weight_kg} kg<\/td>
                     <\/tr>`;
        });
        tbody.innerHTML = html;
    }

    // ==================== RENDER GRAFIK UPGEWICHT 6 BULAN TERAKHIR ====================
    function renderWeightChart(weightRecords) {
        const canvas = document.getElementById('upweightChart');
        const noMsg = document.getElementById('noWeightMessage');

        if (weightChart) {
            weightChart.destroy();
            weightChart = null;
        }

        if (!weightRecords || weightRecords.length < 2) {
            canvas.style.display = 'none';
            noMsg.classList.remove('hidden');
            return;
        }

        canvas.style.display = 'block';
        noMsg.classList.add('hidden');

        // Urutkan berdasarkan tanggal ascending
        const sorted = [...weightRecords].sort(function(a, b) {
            return new Date(a.record_date) - new Date(b.record_date);
        });

        // Ambil maksimal 6 record terakhir
        const lastSix = sorted.slice(-6);
        const labels = lastSix.map(function(record) {
            return new Date(record.record_date).toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
        });
        const values = lastSix.map(function(record) {
            return record.weight_kg;
        });

        weightChart = new Chart(canvas.getContext('2d'), {
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
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'kg' }
                    }
                }
            }
        });
    }

    // ==================== EVENT LISTENERS ====================
    document.addEventListener('DOMContentLoaded', function() {
        loadLivestockList();

        const select = document.getElementById('tagSelect');
        const catatBtn = document.getElementById('btnCatatBerat');

        select.addEventListener('change', function() {
            const id = this.value;
            if (id) {
                currentLivestockId = id;
                loadLivestockDetail(id);
                catatBtn.classList.remove('hidden');
            } else {
                currentLivestockId = null;
                catatBtn.classList.add('hidden');
                document.getElementById('detailInfo').innerHTML = '<div class="col-span-full text-center text-gray-400">Pilih ternak terlebih dahulu</div>';
                document.getElementById('weightHistoryTable').innerHTML = '<td><td colspan="2" class="text-center py-4 text-gray-400">Pilih ternak terlebih dahulu<\/td><\/tr>';
                if (weightChart) {
                    weightChart.destroy();
                    weightChart = null;
                }
                document.getElementById('noWeightMessage').classList.add('hidden');
                document.getElementById('upweightChart').style.display = 'block';
            }
        });

        catatBtn.addEventListener('click', function() {
            if (currentLivestockId) {
                window.location.href = '/livestocks/' + currentLivestockId;
            }
        });
    });
</script>
@endpush