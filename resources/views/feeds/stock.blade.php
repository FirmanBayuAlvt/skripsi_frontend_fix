@extends('layouts.app')

@section('title', 'Stok Pakan')
@section('header-title', 'Stok Pakan')

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
            <i class="fas fa-boxes text-emerald-400 text-xl"></i>
            <h2 class="font-bold text-white text-xl">Tingkat Stok Pakan</h2>
        </div>

        <div id="stock-list" class="space-y-4">
            <div class="text-center text-gray-300">Memuat...</div>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-4">
            <div class="stat-card p-4">
                <span class="text-gray-300 text-sm">Total Nilai Stok</span>
                <p class="text-2xl font-bold text-emerald-300" id="total-value">-</p>
            </div>
            <div class="stat-card p-4">
                <span class="text-gray-300 text-sm">Stok Rendah</span>
                <p class="text-2xl font-bold text-emerald-300" id="low-count">-</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', loadStock);

async function loadStock() {
    try {
        const response = await TernakPark.api.fetchData('/web-api/feeds/stock-levels');
        if (response.success) {
            renderStockData(response.data);
        } else {
            TernakPark.ui.showToast(response.message || 'Gagal memuat data stok', 'error');
        }
    } catch (error) {
        console.error(error);
        TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
    }
}

function renderStockData(data) {
    const container = document.getElementById('stock-list');
    if (!data.feed_types || data.feed_types.length === 0) {
        container.innerHTML = '<div class="text-center text-gray-400">Belum ada data pakan</div>';
        document.getElementById('total-value').innerText = TernakPark.format.currency(0);
        document.getElementById('low-count').innerText = '0';
        return;
    }

    // Cari stok maksimal untuk skala progress bar
    const maxStock = Math.max(...data.feed_types.map(f => f.current_stock), 0);

    let html = '';
    for (const feed of data.feed_types) {
        const stock = feed.current_stock;
        const percentage = maxStock > 0 ? (stock / maxStock) * 100 : 0;
        const isLowStock = stock < 100; // threshold stok rendah

        // Format angka dengan pemisah ribuan
        const formattedStock = new Intl.NumberFormat('id-ID').format(stock);
        const formattedPrice = TernakPark.format.currency(feed.price_per_kg);

        html += `
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-4 hover:border-emerald-400/50 transition">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-semibold text-white text-lg">${escapeHtml(feed.name)}</span>
                            <span class="text-xs bg-white/10 px-2 py-0.5 rounded-full text-gray-300">${escapeHtml(feed.category)}</span>
                        </div>
                        <div class="mt-1 text-sm">
                            <span class="text-gray-400">Stok: </span>
                            <span class="font-mono font-semibold ${isLowStock ? 'text-red-400' : 'text-white'}">${formattedStock} kg</span>
                            <span class="text-gray-400 ml-3">Harga: ${formattedPrice}</span>
                        </div>
                    </div>
                    <div class="w-full md:w-48">
                        <div class="flex justify-between text-xs text-gray-400 mb-1">
                            <span>Persentase</span>
                            <span>${percentage.toFixed(1)}%</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2.5">
                            <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500" style="width: ${percentage}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    container.innerHTML = html;

    document.getElementById('total-value').innerText = TernakPark.format.currency(data.stock_summary.total_value);
    document.getElementById('low-count').innerText = data.stock_summary.low_stock_count;
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}
</script>
@endpush