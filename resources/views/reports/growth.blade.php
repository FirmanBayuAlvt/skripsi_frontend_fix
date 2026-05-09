@extends('layouts.app')

@section('title', 'Laporan Pertumbuhan')
@section('header-title', 'Laporan Pertumbuhan')

@section('content')
<div class="space-y-6">
    <!-- Tombol Kembali ke Laporan -->
    <div class="flex justify-end">
        <a href="{{ route('reports.index') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Laporan
        </a>
    </div>

    <div class="glass-card p-6">
        <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-4">
            <i class="fas fa-chart-line text-emerald-400 text-xl"></i>
            <h2 class="font-bold text-white text-xl">Grafik Pertumbuhan Bobot (Rata-rata 4 Minggu Terakhir)</h2>
        </div>
        <canvas id="growthChart" height="300" class="w-full"></canvas>
        <div id="no-data-message" class="text-center text-gray-400 hidden mt-4">
            <i class="fas fa-chart-line text-4xl mb-2 block"></i>
            Belum ada data berat badan untuk ditampilkan.
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    /**
     * Laporan Pertumbuhan - Grafik Rata-rata Berat 4 Minggu Terakhir
     * Menggunakan Chart.js untuk menampilkan tren pertumbuhan bobot ternak
     */

    let growthChartInstance = null;

    document.addEventListener('DOMContentLoaded', function() {
        loadGrowthData();
    });

    /**
     * Memuat data pertumbuhan dari backend melalui API.
     * Endpoint: /web-api/reports/data?type=growth
     */
    async function loadGrowthData() {
        try {
            const response = await TernakPark.api.fetchData('/web-api/reports/data?type=growth');
            
            if (response.success && response.data) {
                // Ekstrak data dari respons
                const labels = response.data.labels || ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
                const dataValues = response.data.data || [0, 0, 0, 0];
                
                // Cek apakah semua nilai data adalah 0 (berarti belum ada data timbangan)
                const allValuesAreZero = dataValues.every(function(value) {
                    return value === 0;
                });
                
                if (allValuesAreZero) {
                    // Tampilkan pesan bahwa belum ada data
                    document.getElementById('no-data-message').classList.remove('hidden');
                    
                    // Hancurkan chart jika sudah ada
                    if (growthChartInstance !== null) {
                        growthChartInstance.destroy();
                        growthChartInstance = null;
                    }
                    return;
                }
                
                // Sembunyikan pesan "belum ada data" jika sebelumnya tampil
                document.getElementById('no-data-message').classList.add('hidden');
                
                // Render grafik dengan data yang valid
                renderGrowthChart(labels, dataValues);
            } else {
                // Jika respons gagal atau data tidak lengkap
                const errorMessage = response.message || 'Gagal memuat data pertumbuhan';
                TernakPark.ui.showToast(errorMessage, 'error');
                
                // Tampilkan pesan tidak ada data
                document.getElementById('no-data-message').classList.remove('hidden');
                
                // Hancurkan chart jika ada
                if (growthChartInstance !== null) {
                    growthChartInstance.destroy();
                    growthChartInstance = null;
                }
            }
        } catch (error) {
            console.error('Error loading growth data:', error);
            TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
            
            // Tampilkan pesan error di UI
            document.getElementById('no-data-message').classList.remove('hidden');
            
            // Hancurkan chart jika ada
            if (growthChartInstance !== null) {
                growthChartInstance.destroy();
                growthChartInstance = null;
            }
        }
    }

    /**
     * Merender grafik garis (line chart) untuk data pertumbuhan.
     * 
     * @param {string[]} labels - Label untuk sumbu X (minggu atau periode)
     * @param {number[]} data - Nilai berat rata-rata (kg) untuk setiap label
     */
    function renderGrowthChart(labels, data) {
        const canvas = document.getElementById('growthChart');
        
        // Validasi: pastikan elemen canvas ada
        if (!canvas) {
            console.error('Elemen canvas dengan id "growthChart" tidak ditemukan');
            return;
        }
        
        const context = canvas.getContext('2d');
        
        // Destroy instance chart sebelumnya jika ada
        if (growthChartInstance !== null) {
            growthChartInstance.destroy();
            growthChartInstance = null;
        }
        
        // Validasi: pastikan data dan labels valid
        if (!Array.isArray(labels) || labels.length === 0) {
            console.warn('Labels tidak valid atau kosong, menggunakan default');
            labels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
        }
        
        if (!Array.isArray(data) || data.length === 0) {
            console.warn('Data tidak valid atau kosong, menggunakan default 0');
            data = [0, 0, 0, 0];
        }
        
        // Pastikan panjang labels dan data sama
        if (labels.length !== data.length) {
            console.warn('Panjang labels dan data tidak sama, menyesuaikan panjang');
            const maxLength = Math.max(labels.length, data.length);
            while (labels.length < maxLength) labels.push(`Minggu ${labels.length + 1}`);
            while (data.length < maxLength) data.push(0);
        }
        
        // Membuat instance Chart.js baru
        growthChartInstance = new Chart(context, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Rata-rata Berat (kg)',
                        data: data,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        fill: true,
                        tension: 0.3,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'center',
                        labels: {
                            color: '#e2e8f0',
                            font: {
                                weight: 'bold',
                                size: 12,
                            },
                            boxWidth: 12,
                            padding: 15,
                        },
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#a7f3d0',
                        bodyColor: '#cbd5e1',
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(tooltipItem) {
                                const value = tooltipItem.raw;
                                return `Berat: ${value.toFixed(2)} kg`;
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Periode',
                            color: '#94a3b8',
                            font: {
                                size: 12,
                                weight: 'normal',
                            },
                        },
                        ticks: {
                            color: '#e2e8f0',
                            maxRotation: 35,
                            minRotation: 35,
                            font: {
                                size: 11,
                            },
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: true,
                        },
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Berat (kg)',
                            color: '#94a3b8',
                            font: {
                                size: 12,
                                weight: 'normal',
                            },
                        },
                        ticks: {
                            color: '#e2e8f0',
                            callback: function(value) {
                                return value.toFixed(0) + ' kg';
                            },
                            stepSize: 10,
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                        },
                    },
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                elements: {
                    line: {
                        borderJoin: 'round',
                        borderCap: 'round',
                    },
                    point: {
                        hoverRadius: 8,
                        hoverBorderWidth: 3,
                    },
                },
                hover: {
                    mode: 'nearest',
                    intersect: true,
                    animationDuration: 200,
                },
                responsiveAnimationDuration: 300,
            },
        });
    }
</script>
@endpush