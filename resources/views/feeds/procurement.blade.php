@extends('layouts.app')

@section('title', 'Pengadaan Pakan')
@section('header-title', 'Pengadaan Pakan')

@section('content')
<div class="space-y-6">
    <!-- Tombol Kembali ke Manajemen Pakan -->
    <div class="flex justify-end">
        <a href="{{ route('feeds.index') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Pakan
        </a>
    </div>

    <!-- Form Catat Pengadaan Pakan Baru -->
    <div class="glass-card p-5">
        <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
            <i class="fas fa-truck text-emerald-400"></i>
            <h3 class="font-bold text-white text-lg">Catat Pengadaan Pakan Baru</h3>
        </div>
        <form id="purchase-form" class="space-y-4 no-global-loading" data-no-global-loading="true">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-200 text-sm font-medium mb-1">Tanggal</label>
                    <input type="date" name="date" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-gray-200 text-sm font-medium mb-1">Supplier</label>
                    <input type="text" name="supplier" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white placeholder:text-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Supplier (opsional)">
                </div>
                <div>
                    <label class="block text-gray-200 text-sm font-medium mb-1">Bahan Pakan</label>
                    <input type="text" name="feed_name" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white placeholder:text-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Contoh: Rumput Pakchong">
                </div>
                <div>
                    <label class="block text-gray-200 text-sm font-medium mb-1">Harga per unit</label>
                    <input type="number" step="0.01" name="price_per_unit" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white placeholder:text-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Harga (opsional)">
                </div>
                <div>
                    <label class="block text-gray-200 text-sm font-medium mb-1">Quantity</label>
                    <input type="number" step="0.01" name="quantity" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white placeholder:text-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-gray-200 text-sm font-medium mb-1">Satuan</label>
                    <input type="text" name="unit" value="kg" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white placeholder:text-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="kg / sak">
                </div>
            </div>
            <div>
                <label class="block text-gray-200 text-sm font-medium mb-1">Catatan</label>
                <textarea name="notes" rows="2" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white placeholder:text-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"></textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-5 py-2 rounded-xl shadow-md transition-all transform hover:scale-[1.02] flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Pengadaan
                </button>
            </div>
        </form>
    </div>

    <!-- Filter Data Pengadaan -->
    <div class="glass-card p-5">
        <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
            <i class="fas fa-filter text-emerald-400"></i>
            <h3 class="font-bold text-white text-lg">Filter Data Pengadaan</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-gray-200 text-sm font-medium mb-1">Tanggal Mulai</label>
                <input type="date" id="filter-start-date" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-gray-200 text-sm font-medium mb-1">Tanggal Akhir</label>
                <input type="date" id="filter-end-date" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-gray-200 text-sm font-medium mb-1">Bahan Pakan</label>
                <select id="filter-feed-name" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="">Semua</option>
                </select>
            </div>
            <div class="flex items-end md:col-span-3">
                <button id="btn-apply-filter" class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-5 py-2 rounded-xl shadow-md transition-all transform hover:scale-[1.02] flex items-center gap-2">
                    <i class="fas fa-search"></i> Terapkan Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Grafik Pengadaan Per Bulan (Jumlah Record) -->
    <div class="glass-card p-5">
        <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
            <i class="fas fa-chart-bar text-emerald-400"></i>
            <h3 class="font-bold text-white text-lg">Pengadaan Pakan Per Bulan (Jumlah Record)</h3>
        </div>
        <canvas id="monthlyPurchaseChart" width="100%" height="300" style="max-height: 400px;"></canvas>
    </div>

    <!-- Tabel Realisasi Pengadaan -->
    <div class="glass-card p-0 overflow-hidden">
        <div class="flex items-center gap-2 border-b border-white/10 p-5">
            <i class="fas fa-list text-emerald-400"></i>
            <h3 class="font-bold text-white text-lg">Realisasi Pengadaan Pakan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="custom-table w-full text-sm text-white">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr class="text-gray-300 text-xs uppercase">
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Supplier</th>
                        <th class="px-4 py-3 text-left">Bahan</th>
                        <th class="px-4 py-3 text-left">Harga/Qty</th>
                        <th class="px-4 py-3 text-left">QTY (sak/kg)</th>
                    </tr>
                </thead>
                <tbody id="purchase-table-body">
                    <tr><td colspan="5" class="text-center py-8 text-gray-400">Memuat data...<\/td><\/tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let currentFilters = {};
let purchaseChart = null;

document.addEventListener('DOMContentLoaded', function() {
    loadProcurementData();
    loadFeedOptions();

    const purchaseForm = document.getElementById('purchase-form');
    if (purchaseForm) {
        purchaseForm.addEventListener('submit', async function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            data.quantity = parseFloat(data.quantity) || 0;
            data.price_per_unit = data.price_per_unit ? parseFloat(data.price_per_unit) : null;
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';

            try {
                const response = await fetch('/web-api/feeds/purchase-record', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                
                // Periksa apakah response JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Response bukan JSON:', text.substring(0, 200));
                    throw new Error('Server mengembalikan HTML. Mungkin endpoint tidak ditemukan atau session habis.');
                }
                
                const result = await response.json();
                if (result.success) {
                    TernakPark.ui.showToast('Pengadaan pakan berhasil dicatat', 'success');
                    this.reset();
                    // Isi tanggal hari ini
                    const today = new Date().toISOString().split('T')[0];
                    this.querySelector('input[name="date"]').value = today;
                    await loadProcurementData();
                } else {
                    TernakPark.ui.showToast(result.message || 'Gagal mencatat pengadaan', 'error');
                }
            } catch (error) {
                console.error(error);
                TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        });
    }

    const applyFilterButton = document.getElementById('btn-apply-filter');
    if (applyFilterButton) {
        applyFilterButton.addEventListener('click', function() {
            currentFilters = {
                start_date: document.getElementById('filter-start-date').value,
                end_date: document.getElementById('filter-end-date').value,
                feed_name: document.getElementById('filter-feed-name').value
            };
            loadProcurementData();
        });
    }
});

async function loadFeedOptions() {
    try {
        const response = await TernakPark.api.fetchData('/web-api/feeds/data?per_page=1000');
        if (response.success && response.data.feed_types) {
            const filterSelect = document.getElementById('filter-feed-name');
            filterSelect.innerHTML = '<option value="">Semua</option>';
            const uniqueNames = [...new Set(response.data.feed_types.map(f => f.name))];
            for (const name of uniqueNames) {
                filterSelect.innerHTML += `<option value="${escapeHtml(name)}">${escapeHtml(name)}</option>`;
            }
        } else {
            document.getElementById('filter-feed-name').innerHTML = '<option value="">Semua</option>';
        }
    } catch (error) {
        console.error(error);
        TernakPark.ui.showToast('Gagal memuat opsi bahan pakan', 'error');
        document.getElementById('filter-feed-name').innerHTML = '<option value="">Semua</option>';
    }
}

async function loadProcurementData() {
    const queryParameters = new URLSearchParams();
    if (currentFilters.start_date) {
        queryParameters.append('start_date', currentFilters.start_date);
    }
    if (currentFilters.end_date) {
        queryParameters.append('end_date', currentFilters.end_date);
    }
    if (currentFilters.feed_name) {
        queryParameters.append('feed_name', currentFilters.feed_name);
    }

    try {
        const response = await TernakPark.api.fetchData(`/web-api/feeds/procurement-data?${queryParameters.toString()}`);
        if (response.success && response.data) {
            const data = response.data;
            
            // Grafik bulanan
            if (data.monthly_purchase_count && data.monthly_purchase_count.length > 0) {
                const bulanLabels = data.monthly_purchase_count.map(item => item.month);
                const recordCounts = data.monthly_purchase_count.map(item => item.total_records);
                
                if (purchaseChart) {
                    purchaseChart.destroy();
                }
                const ctx = document.getElementById('monthlyPurchaseChart').getContext('2d');
                purchaseChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: bulanLabels,
                        datasets: [{
                            label: 'Jumlah Record',
                            data: recordCounts,
                            backgroundColor: '#f59e0b',
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                labels: { color: '#e2e8f0' }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Jumlah: ${context.raw} record`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: { color: '#e2e8f0' },
                                grid: { display: false }
                            },
                            y: {
                                ticks: { color: '#e2e8f0', stepSize: 1 },
                                grid: { color: 'rgba(255,255,255,0.05)' },
                                title: {
                                    display: true,
                                    text: 'Jumlah Record',
                                    color: '#94a3b8'
                                }
                            }
                        }
                    }
                });
            } else {
                if (purchaseChart) {
                    purchaseChart.destroy();
                    purchaseChart = null;
                }
                // Tampilkan pesan tidak ada data di canvas
                const canvas = document.getElementById('monthlyPurchaseChart');
                if (canvas) {
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.font = '14px Inter';
                    ctx.fillStyle = '#94a3b8';
                    ctx.fillText('Belum ada data pengadaan', canvas.width/2 - 80, canvas.height/2);
                }
            }
            
            // Tabel realisasi pengadaan
            const tableBody = document.getElementById('purchase-table-body');
            if (data.purchases && data.purchases.length > 0) {
                let tableHtml = '';
                for (const purchase of data.purchases) {
                    tableHtml += `
                        <tr class="border-b border-white/10 hover:bg-white/5 transition">
                            <td class="px-4 py-3">${escapeHtml(purchase.date)}</td>
                            <td class="px-4 py-3">${escapeHtml(purchase.supplier)}</td>
                            <td class="px-4 py-3">${escapeHtml(purchase.feed_name)}</td>
                            <td class="px-4 py-3">${formatRupiah(purchase.price_per_unit)}</td>
                            <td class="px-4 py-3">${purchase.quantity} ${purchase.unit}</td>
                        </tr>
                    `;
                }
                tableBody.innerHTML = tableHtml;
            } else {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-gray-400">Belum ada data pengadaan</td></tr>';
            }
        } else {
            TernakPark.ui.showToast(response.message || 'Gagal memuat data pengadaan', 'error');
            document.getElementById('purchase-table-body').innerHTML = '<tr><td colspan="5" class="text-center py-8 text-red-400">Gagal memuat data</td></tr>';
        }
    } catch (error) {
        console.error(error);
        TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
        document.getElementById('purchase-table-body').innerHTML = '<tr><td colspan="5" class="text-center py-8 text-red-400">Koneksi error</td></tr>';
    }
}

function formatRupiah(angka) {
    if (angka === undefined || angka === null || angka === 0) return 'Rp 0';
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