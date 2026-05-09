@extends('layouts.app')

@section('title', 'Laporan & Analisis')
@section('header-title', 'Laporan & Analisis')

@section('content')
<div class="space-y-6">
    <!-- Tombol Kembali ke Dashboard -->
    <div class="flex justify-end">
        <a href="{{ route('dashboard') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- Kartu Laporan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Laporan Performa -->
        <div class="glass-card p-6 cursor-pointer hover:border-emerald-400/70 transition-all transform hover:scale-[1.02]" onclick="window.location='{{ route('reports.performance') }}'">
            <div class="flex flex-col items-center text-center">
                <div class="text-5xl mb-3">📊</div>
                <h3 class="font-bold text-white text-lg">Laporan Performa</h3>
                <p class="text-gray-300 text-sm mt-1">Analisis pertumbuhan dan efisiensi</p>
            </div>
        </div>

        <!-- Laporan Pertumbuhan -->
        <div class="glass-card p-6 cursor-pointer hover:border-emerald-400/70 transition-all transform hover:scale-[1.02]" onclick="window.location='{{ route('reports.growth') }}'">
            <div class="flex flex-col items-center text-center">
                <div class="text-5xl mb-3">📈</div>
                <h3 class="font-bold text-white text-lg">Laporan Pertumbuhan</h3>
                <p class="text-gray-300 text-sm mt-1">Tracking berat badan ternak</p>
            </div>
        </div>
    </div>

    {{-- Ringkasan Cepat --}}
    <div class="glass-card p-6">
        <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
            <i class="fas fa-chart-simple text-emerald-400"></i>
            <h2 class="font-bold text-white text-lg">Ringkasan Cepat</h2>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white/5 rounded-xl p-4 text-center border border-white/10">
                <i class="fas fa-paw text-emerald-400 text-xl mb-2 block"></i>
                <p class="text-gray-300 text-sm">Total Ternak</p>
                <p class="text-2xl font-bold text-white" id="total-livestock">-</p>
            </div>
            <div class="bg-white/5 rounded-xl p-4 text-center border border-white/10">
                <i class="fas fa-warehouse text-emerald-400 text-xl mb-2 block"></i>
                <p class="text-gray-300 text-sm">Total Kandang</p>
                <p class="text-2xl font-bold text-white" id="total-pens">-</p>
            </div>
            <div class="bg-white/5 rounded-xl p-4 text-center border border-white/10">
                <i class="fas fa-seedling text-emerald-400 text-xl mb-2 block"></i>
                <p class="text-gray-300 text-sm">Jenis Pakan</p>
                <p class="text-2xl font-bold text-white" id="total-feeds">-</p>
            </div>
            <div class="bg-white/5 rounded-xl p-4 text-center border border-white/10">
                <i class="fas fa-boxes text-emerald-400 text-xl mb-2 block"></i>
                <p class="text-gray-300 text-sm">Stok Pakan (kg)</p>
                <p class="text-2xl font-bold text-white" id="total-feed-stock">-</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /**
     * Halaman Laporan & Analisis - Ringkasan Cepat
     * Memuat data ringkasan dari backend (total ternak, kandang, jenis pakan, stok pakan)
     * dan menampilkannya pada kartu statistik.
     */

    // Event listener saat DOM selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        loadSummaryData();
    });

    /**
     * Memuat data ringkasan dari endpoint API.
     * Endpoint: /web-api/reports/data?type=summary
     */
    async function loadSummaryData() {
        try {
            // Panggil API melalui TernakPark helper
            const response = await TernakPark.api.fetchData('/web-api/reports/data?type=summary');

            // Cek apakah respons sukses dan memiliki data
            if (response.success && response.data) {
                // Update elemen dengan data yang diterima
                const totalLivestockElement = document.getElementById('total-livestock');
                const totalPensElement = document.getElementById('total-pens');
                const totalFeedsElement = document.getElementById('total-feeds');
                const totalFeedStockElement = document.getElementById('total-feed-stock');

                if (totalLivestockElement) {
                    totalLivestockElement.innerText = (response.data.total_livestocks || 0).toLocaleString('id-ID');
                }

                if (totalPensElement) {
                    totalPensElement.innerText = (response.data.total_pens || 0).toLocaleString('id-ID');
                }

                if (totalFeedsElement) {
                    totalFeedsElement.innerText = (response.data.total_feed_types || 0).toLocaleString('id-ID');
                }

                if (totalFeedStockElement) {
                    totalFeedStockElement.innerText = (response.data.total_feed_stock_kg || 0).toLocaleString('id-ID');
                }
            } else {
                // Jika respons gagal, tampilkan pesan error dan set nilai default 0
                const errorMessage = response.message || 'Gagal memuat ringkasan';
                TernakPark.ui.showToast(errorMessage, 'error');

                // Set nilai default 0 untuk semua statistik
                setDefaultSummaryValues();
            }
        } catch (error) {
            // Tangani error koneksi atau lainnya
            console.error('Error loading summary data:', error);
            TernakPark.ui.showToast('Koneksi error saat memuat ringkasan', 'error');

            // Set nilai default 0 untuk semua statistik
            setDefaultSummaryValues();
        }
    }

    /**
     * Mengatur nilai default (0) untuk semua elemen ringkasan.
     * Digunakan ketika gagal memuat data dari server.
     */
    function setDefaultSummaryValues() {
        const totalLivestockElement = document.getElementById('total-livestock');
        const totalPensElement = document.getElementById('total-pens');
        const totalFeedsElement = document.getElementById('total-feeds');
        const totalFeedStockElement = document.getElementById('total-feed-stock');

        if (totalLivestockElement) {
            totalLivestockElement.innerText = '0';
        }

        if (totalPensElement) {
            totalPensElement.innerText = '0';
        }

        if (totalFeedsElement) {
            totalFeedsElement.innerText = '0';
        }

        if (totalFeedStockElement) {
            totalFeedStockElement.innerText = '0';
        }
    }
</script>
@endpush