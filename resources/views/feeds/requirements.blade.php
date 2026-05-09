@extends('layouts.app')

@section('title', 'Kebutuhan Pakan')
@section('header-title', 'Kebutuhan Pakan')

@section('content')
<div class="space-y-6">
    <!-- Tombol Kembali ke Manajemen Pakan -->
    <div class="flex justify-end">
        <a href="{{ route('feeds.index') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Pakan
        </a>
    </div>

    <div class="glass-card p-6">
        <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
            <i class="fas fa-calculator text-emerald-400 text-xl"></i>
            <h2 class="font-bold text-white text-xl">Kebutuhan Pakan Harian</h2>
        </div>

        <!-- Ringkasan kebutuhan harian, mingguan, bulanan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white/5 border border-white/10 rounded-xl p-5 shadow-md hover:border-emerald-400/50 transition">
                <p class="text-gray-300 text-sm">Harian</p>
                <p class="text-3xl font-bold text-emerald-300" id="total-daily-kg">0 kg</p>
                <p class="text-gray-300 text-sm mt-1">Biaya: <span id="total-daily-cost">Rp 0</span></p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-xl p-5 shadow-md hover:border-emerald-400/50 transition">
                <p class="text-gray-300 text-sm">Mingguan</p>
                <p class="text-3xl font-bold text-emerald-300" id="total-weekly-kg">0 kg</p>
                <p class="text-gray-300 text-sm mt-1">Biaya: <span id="total-weekly-cost">Rp 0</span></p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-xl p-5 shadow-md hover:border-emerald-400/50 transition">
                <p class="text-gray-300 text-sm">Bulanan</p>
                <p class="text-3xl font-bold text-emerald-300" id="total-monthly-kg">0 kg</p>
                <p class="text-gray-300 text-sm mt-1">Biaya: <span id="total-monthly-cost">Rp 0</span></p>
            </div>
        </div>

        <!-- Komposisi Harian -->
        <h3 class="text-white font-semibold text-lg mb-3">Komposisi Harian</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center hover:border-emerald-400/50 transition">
                <p class="font-medium text-gray-200">Silase</p>
                <p class="text-2xl font-semibold text-emerald-300" id="daily-silase">0 kg</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center hover:border-emerald-400/50 transition">
                <p class="font-medium text-gray-200">Complete Feed Jember</p>
                <p class="text-2xl font-semibold text-emerald-300" id="daily-cf-jember">0 kg</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center hover:border-emerald-400/50 transition">
                <p class="font-medium text-gray-200">Jagung Halus</p>
                <p class="text-2xl font-semibold text-emerald-300" id="daily-jagung-halus">0 kg</p>
            </div>
        </div>

        <!-- Pembagian Pakan per Kategori Kandang -->
        <h3 class="text-white font-semibold text-lg mb-4">Pembagian Pakan per Kategori Kandang</h3>
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <!-- Grafik Kebutuhan Pakan per Kategori -->
            <div class="bg-white/5 border border-white/10 rounded-xl p-4 shadow-md">
                <h4 class="font-semibold text-gray-200 mb-3 text-center">Kebutuhan Pakan per Kategori</h4>
                <div class="relative h-72 w-full">
                    <canvas id="categoryFeedChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Daftar Kategori Kandang dengan kebutuhan -->
            <div class="grid grid-cols-1 gap-3 overflow-y-auto max-h-96 pr-2">
                <div id="category-list" class="space-y-3">
                    <div class="text-center text-gray-400 py-4">Memuat data...</div>
                </div>
            </div>
        </div>

        <!-- Penggunaan Pakan Per Jenis & Tabel Harian -->
        <div class="mt-8 grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="bg-white/5 border border-white/10 rounded-xl p-4 shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="font-semibold text-gray-200">Penggunaan Pakan Per Jenis</h4>
                        <p class="text-xs text-gray-400">Total 30 hari terakhir</p>
                    </div>
                </div>
                <div class="relative h-80 w-full">
                    <canvas id="feedUsageChart" class="w-full h-full"></canvas>
                </div>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-xl p-4 shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="font-semibold text-gray-200">Penggunaan Pakan Harian</h4>
                        <p class="text-xs text-gray-400">Catatan pemakaian terbaru</p>
                    </div>
                </div>
                <div class="overflow-x-auto max-h-80">
                    <table class="min-w-full text-left text-sm text-gray-200">
                        <thead class="bg-white/10 sticky top-0">
                            <tr>
                                <th class="px-3 py-2 border-b border-white/10">Tanggal</th>
                                <th class="px-3 py-2 border-b border-white/10">Jenis Pakan</th>
                                <th class="px-3 py-2 border-b border-white/10">Kandang</th>
                                <th class="px-3 py-2 border-b border-white/10">Keluar (kg)</th>
                            </tr>
                        </thead>
                        <tbody id="recent-usage-table-body">
                            <tr><td colspan="4" class="text-center py-6 text-gray-400">Memuat...<\/td><\/tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let categoryChart = null;
    let feedUsageChart = null;

    document.addEventListener('DOMContentLoaded', function() {
        loadRequirements();
    });

    async function loadRequirements() {
        try {
            const response = await TernakPark.api.fetchData('/web-api/feeds/requirements');
            if (response.success && response.data) {
                updateRequirementsUI(response.data);
            } else {
                TernakPark.ui.showToast(response.message || 'Gagal memuat data kebutuhan pakan', 'error');
            }
        } catch (error) {
            console.error(error);
            TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
        }
    }

    function updateRequirementsUI(data) {
        // 1. Update ringkasan harian, mingguan, bulanan
        const daily = data.requirements?.daily || { total_kg: 0, cost: 0 };
        const weekly = data.requirements?.weekly || { total_kg: 0, cost: 0 };
        const monthly = data.requirements?.monthly || { total_kg: 0, cost: 0 };

        document.getElementById('total-daily-kg').innerText = daily.total_kg.toFixed(2) + ' kg';
        document.getElementById('total-daily-cost').innerText = formatRupiah(daily.cost);
        document.getElementById('total-weekly-kg').innerText = weekly.total_kg.toFixed(2) + ' kg';
        document.getElementById('total-weekly-cost').innerText = formatRupiah(weekly.cost);
        document.getElementById('total-monthly-kg').innerText = monthly.total_kg.toFixed(2) + ' kg';
        document.getElementById('total-monthly-cost').innerText = formatRupiah(monthly.cost);

        // 2. Komposisi harian (dari data.requirements.daily.composition)
        const composition = daily.composition || { silase: 0, cf_jember: 0, jagung_halus: 0 };
        document.getElementById('daily-silase').innerText = composition.silase.toFixed(2) + ' kg';
        document.getElementById('daily-cf-jember').innerText = composition.cf_jember.toFixed(2) + ' kg';
        document.getElementById('daily-jagung-halus').innerText = composition.jagung_halus.toFixed(2) + ' kg';

        // 3. Tampilkan daftar kategori kandang (pen_category_requirements)
        const categoryRequirements = data.pen_category_requirements || {};
        const container = document.getElementById('category-list');
        const categories = Object.keys(categoryRequirements);
        if (categories.length === 0) {
            container.innerHTML = '<div class="text-center text-gray-400 py-4">Belum ada data kategori kandang<\/div>';
        } else {
            let html = '';
            for (const category of categories) {
                const req = categoryRequirements[category];
                html += `
                    <div class="bg-black/20 rounded-xl p-3 border border-white/10 hover:border-emerald-400/50 transition">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-white">${escapeHtml(category)}</span>
                            <span class="text-sm text-emerald-300">${req.daily_kg} kg/hari</span>
                        </div>
                        <div class="text-xs text-gray-400 mt-1">Jumlah ternak: ${req.livestock_count} ekor</div>
                    </div>
                `;
            }
            container.innerHTML = html;
        }

        // 4. Grafik kebutuhan pakan per kategori (pen_categories)
        const penCategories = data.pen_categories || [];
        if (penCategories.length > 0) {
            const labels = [];
            const values = [];
            for (const cat of penCategories) {
                labels.push(cat.category || cat.name || 'Kategori');
                values.push(Number(cat.daily_ration_kg) || 0);
            }
            const canvas = document.getElementById('categoryFeedChart');
            if (categoryChart) categoryChart.destroy();
            categoryChart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Kebutuhan Harian (kg)',
                        data: values,
                        backgroundColor: '#10b981',
                        borderRadius: 8,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { position: 'top', labels: { color: '#e2e8f0' } },
                        tooltip: { callbacks: { label: function(ctx) { return ctx.raw.toFixed(2) + ' kg'; } } }
                    },
                    scales: {
                        x: { ticks: { color: '#e2e8f0' }, grid: { display: false } },
                        y: { beginAtZero: true, ticks: { color: '#e2e8f0' }, grid: { color: 'rgba(255,255,255,0.05)' } }
                    }
                }
            });
        }

        // 5. Grafik penggunaan pakan per jenis (usage_by_feed)
        const usageByFeed = data.usage_by_feed || [];
        if (usageByFeed.length > 0) {
            const feedLabels = usageByFeed.map(function(item) { return item.feed_name; });
            const feedValues = usageByFeed.map(function(item) { return item.total_kg; });
            const ctx = document.getElementById('feedUsageChart').getContext('2d');
            if (feedUsageChart) feedUsageChart.destroy();
            feedUsageChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: feedLabels,
                    datasets: [{
                        label: 'Total Pemakaian (kg)',
                        data: feedValues,
                        backgroundColor: '#3b82f6',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    indexAxis: 'y',
                    plugins: { legend: { position: 'top', labels: { color: '#e2e8f0' } } },
                    scales: {
                        x: { ticks: { color: '#e2e8f0' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                        y: { ticks: { color: '#e2e8f0' }, grid: { display: false } }
                    }
                }
            });
        } else {
            const parent = document.getElementById('feedUsageChart').parentElement;
            document.getElementById('feedUsageChart').style.display = 'none';
            parent.innerHTML = '<div class="text-center text-gray-400 py-10">Data penggunaan pakan per jenis belum tersedia.</div>';
        }

        // 6. Tabel penggunaan terkini
        const recentUsage = data.recent_usage || [];
        const tableBody = document.getElementById('recent-usage-table-body');
        if (recentUsage.length > 0) {
            let html = '';
            for (const rec of recentUsage) {
                html += `
                    <tr class="border-b border-white/10">
                        <td class="px-3 py-2">${escapeHtml(rec.date)}<\/td>
                        <td class="px-3 py-2">${escapeHtml(rec.feed)}<\/td>
                        <td class="px-3 py-2">${escapeHtml(rec.pen)}<\/td>
                        <td class="px-3 py-2 text-emerald-300 font-semibold">${rec.quantity_kg}<\/td>
                    <\/tr>
                `;
            }
            tableBody.innerHTML = html;
        } else {
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-6 text-gray-400">Belum ada data penggunaan<\/td><\/tr>';
        }
    }

    function formatRupiah(angka) {
        if (angka === undefined || angka === null) return 'Rp 0';
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
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