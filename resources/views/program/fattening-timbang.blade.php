@extends('layouts.app')

@section('title', 'Data Timbang - Fattening')
@section('header-title', 'Data Timbang (Fattening)')

@section('content')
<div class="space-y-6">
    <div class="flex justify-end">
        <a href="{{ route('program.fattening') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Fattening
        </a>
    </div>

    <div class="glass-card p-6">
        <!-- Filter Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="text-gray-300 text-sm">Tagging</label>
                <select id="filterTagging" class="w-full bg-white/10 border border-white/20 rounded-xl px-3 py-2 text-white">
                    <option value="">Semua</option>
                </select>
            </div>
            <div>
                <label class="text-gray-300 text-sm">Jenis Domba</label>
                <select id="filterJenis" class="w-full bg-white/10 border border-white/20 rounded-xl px-3 py-2 text-white">
                    <option value="">Semua</option>
                </select>
            </div>
            <div>
                <label class="text-gray-300 text-sm">Kategori Kandang</label>
                <select id="filterKategori" class="w-full bg-white/10 border border-white/20 rounded-xl px-3 py-2 text-white">
                    <option value="">Semua</option>
                </select>
            </div>
        </div>

        <!-- Statistik ADG Minus & Upweight Minus -->
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white/5 rounded-xl p-4">
                <p class="text-gray-300">🐏 Qty ternak dengan ADG minus 2x berturut-turut</p>
                <p class="text-3xl font-bold text-red-300" id="qtyMinus2x">0</p>
                <div class="overflow-x-auto mt-3">
                    <table class="custom-table w-full text-xs">
                        <thead>
                            <tr>
                                <th>Tagging</th>
                                <th>Kandang</th>
                                <th>Kategori</th>
                                <th>Jenis Domba</th>
                                <th>ADG Terbaru</th>
                            </tr>
                        </thead>
                        <tbody id="tabelMinus2x"></tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white/5 rounded-xl p-4">
                <p class="text-gray-300">📈 Qty ternak upweight minus timbang terakhir</p>
                <p class="text-3xl font-bold text-emerald-300" id="qtyUpweightMinus">0</p>
                <div class="overflow-x-auto mt-3">
                    <table class="custom-table w-full text-xs">
                        <thead>
                            <tr>
                                <th>Tagging</th>
                                <th>Kandang</th>
                                <th>Kategori</th>
                                <th>Jenis Domba</th>
                                <th>ADG Terbaru</th>
                            </tr>
                        </thead>
                        <tbody id="tabelUpweightMinus"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Data Timbang Per Bulan -->
        <div class="mt-6">
            <h4 class="font-semibold text-white">📋 Data Timbang Ternak Per Bulan</h4>
            <div class="overflow-x-auto">
                <table class="custom-table w-full text-sm">
                    <thead>
                        <tr>
                            <th>Tagging</th>
                            <th>BB IN</th>
                            <th>BB 4 bln lalu</th>
                            <th>BB 3 bln lalu</th>
                            <th>BB 2 bln lalu</th>
                            <th>BB Bulan Lalu</th>
                            <th>BB Terbaru</th>
                        </tr>
                    </thead>
                    <tbody id="tabelTimbangData"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ==================== DOM READY ====================
    document.addEventListener('DOMContentLoaded', function() {
        loadFilterOptions();
        loadDataTimbang();

        document.getElementById('filterTagging').addEventListener('change', loadDataTimbang);
        document.getElementById('filterJenis').addEventListener('change', loadDataTimbang);
        document.getElementById('filterKategori').addEventListener('change', loadDataTimbang);
    });

    // ==================== LOAD FILTER OPTIONS ====================
    async function loadFilterOptions() {
        try {
            const [livestockRes, penRes] = await Promise.all([
                TernakPark.api.fetchData('/web-api/livestocks/data?per_page=1000&status=active'),
                TernakPark.api.fetchData('/web-api/pens/data')
            ]);

            if (livestockRes.success) {
                const taggingSelect = document.getElementById('filterTagging');
                taggingSelect.innerHTML = '<option value="">Semua</option>';
                livestockRes.data.livestocks.forEach(function(livestock) {
                    taggingSelect.innerHTML += '<option value="' + escapeHtml(livestock.ear_tag) + '">' + escapeHtml(livestock.ear_tag) + '</option>';
                });

                const jenisSelect = document.getElementById('filterJenis');
                const breeds = [...new Set(livestockRes.data.livestocks.map(function(l) {
                    return l.breed_type;
                }))];
                jenisSelect.innerHTML = '<option value="">Semua</option>';
                breeds.forEach(function(breed) {
                    jenisSelect.innerHTML += '<option value="' + escapeHtml(breed) + '">' + escapeHtml(breed.replace(/_/g, ' ')) + '</option>';
                });
            }

            if (penRes.success) {
                const kategoriSelect = document.getElementById('filterKategori');
                const categories = [...new Set(penRes.data.pens.map(function(p) {
                    return p.category;
                }))];
                kategoriSelect.innerHTML = '<option value="">Semua</option>';
                categories.forEach(function(category) {
                    kategoriSelect.innerHTML += '<option value="' + escapeHtml(category) + '">' + escapeHtml(category) + '</option>';
                });
            }
        } catch(error) {
            console.error('Error loading filter options:', error);
            TernakPark.ui.showToast('Gagal memuat opsi filter', 'error');
        }
    }

    // ==================== LOAD DATA TIMBANG ====================
    async function loadDataTimbang() {
        const tagging = document.getElementById('filterTagging').value;
        const jenis = document.getElementById('filterJenis').value;
        const kategori = document.getElementById('filterKategori').value;

        const queryParameters = new URLSearchParams();
        if (tagging) queryParameters.append('tagging', tagging);
        if (jenis) queryParameters.append('jenis', jenis);
        if (kategori) queryParameters.append('kategori', kategori);

        try {
            const response = await TernakPark.api.fetchData('/web-api/program/fattening-timbang?' + queryParameters.toString());
            if (response.success) {
                const data = response.data;
                document.getElementById('qtyMinus2x').innerText = data.qty_minus_2x || 0;
                document.getElementById('qtyUpweightMinus').innerText = data.qty_upweight_minus || 0;

                renderSimpleTable('tabelMinus2x', data.minus_2x_list || [], ['ear_tag', 'pen_name', 'pen_category', 'breed_type', 'adg']);
                renderSimpleTable('tabelUpweightMinus', data.upweight_minus_list || [], ['ear_tag', 'pen_name', 'pen_category', 'breed_type', 'adg']);
                renderSimpleTable('tabelTimbangData', data.timbang_per_bulan || [], ['ear_tag', 'bb_in', 'bb_4', 'bb_3', 'bb_2', 'bb_1', 'bb_now']);
            } else {
                TernakPark.ui.showToast(response.message || 'Gagal memuat data timbang', 'error');
            }
        } catch(error) {
            console.error('Error loading timbang data:', error);
            TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
        }
    }

    // ==================== RENDER SIMPLE TABLE ====================
    function renderSimpleTable(tbodyId, data, fields) {
        const tableBody = document.getElementById(tbodyId);
        if (!data || data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="' + fields.length + '" class="text-center py-2 text-gray-400">Tidak ada data<\/td><\/tr>';
            return;
        }

        let html = '';
        for (let i = 0; i < data.length; i++) {
            const row = data[i];
            html += '<tr class="border-b border-white/10">';
            for (let j = 0; j < fields.length; j++) {
                const field = fields[j];
                let value = row[field] !== undefined ? row[field] : '-';
                if (field === 'adg') {
                    value = parseFloat(value).toFixed(4);
                }
                html += '<td class="px-2 py-1">' + escapeHtml(String(value)) + '<\/td>';
            }
            html += '<\/tr>';
        }
        tableBody.innerHTML = html;
    }

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
</script>
@endpush