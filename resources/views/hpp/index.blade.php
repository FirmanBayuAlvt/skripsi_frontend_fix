@extends('layouts.app')

@section('title', 'HPP Ternak')
@section('header-title', 'Harga Pokok Produksi (HPP)')

@section('page-header')
<div class="flex flex-wrap justify-between items-center gap-3">
    <div class="flex items-center gap-2">
        <div class="h-8 w-1 bg-gradient-to-b from-emerald-300 to-teal-500 rounded-full"></div>
        <p class="text-emerald-200 font-medium tracking-wide">HARGA POKOK PRODUKSI</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('dashboard') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-300 text-sm">Total HPP Jantan</p>
                    <p class="stat-value" id="total-hpp-jantan">Rp -</p>
                </div>
                <div class="bg-emerald-500/20 p-3 rounded-2xl">
                    <i class="fas fa-mars text-emerald-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-300 text-sm">Total HPP Betina</p>
                    <p class="stat-value" id="total-hpp-betina">Rp -</p>
                </div>
                <div class="bg-pink-500/20 p-3 rounded-2xl">
                    <i class="fas fa-venus text-pink-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-300 text-sm">Jantan Breeding</p>
                    <p class="stat-value" id="qty-jantan-breeding">-</p>
                </div>
                <div class="bg-blue-500/20 p-3 rounded-2xl">
                    <i class="fas fa-heartbeat text-blue-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-300 text-sm">Total HPP Jantan Breeding</p>
                    <p class="stat-value" id="total-hpp-jantan-breeding">Rp -</p>
                </div>
                <div class="bg-purple-500/20 p-3 rounded-2xl">
                    <i class="fas fa-chart-line text-purple-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-300 text-sm">Jantan Fattening</p>
                    <p class="stat-value" id="qty-jantan-fattening">-</p>
                </div>
                <div class="bg-amber-500/20 p-3 rounded-2xl">
                    <i class="fas fa-weight-hanging text-amber-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-300 text-sm">Total HPP Jantan Fattening</p>
                    <p class="stat-value" id="total-hpp-jantan-fattening">Rp -</p>
                </div>
                <div class="bg-orange-500/20 p-3 rounded-2xl">
                    <i class="fas fa-chart-simple text-orange-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-300 text-sm">Total Betina</p>
                    <p class="stat-value" id="qty-betina">-</p>
                </div>
                <div class="bg-rose-500/20 p-3 rounded-2xl">
                    <i class="fas fa-female text-rose-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-300 text-sm">Total HPP Betina</p>
                    <p class="stat-value" id="total-hpp-betina-detail">Rp -</p>
                </div>
                <div class="bg-teal-500/20 p-3 rounded-2xl">
                    <i class="fas fa-coins text-teal-300 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Detail HPP -->
    <div class="glass-card p-0 overflow-hidden">
        <div class="flex items-center gap-2 border-b border-white/10 p-5">
            <i class="fas fa-table-list text-emerald-400 text-xl"></i>
            <h3 class="font-bold text-white text-lg">Detail HPP per Ternak</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="custom-table w-full text-sm text-white">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr class="text-gray-300 text-xs uppercase">
                        <th class="px-6 py-3 text-left">Tagging</th>
                        <th class="px-6 py-3 text-left">HPP Pembelian</th>
                        <th class="px-6 py-3 text-left">Pakan</th>
                        <th class="px-6 py-3 text-left">Operasional</th>
                        <th class="px-6 py-3 text-left">Total HPP</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody id="hpp-table">
                    <td>
                        <td colspan="6" class="text-center py-8 text-gray-400">Memuat data...<\/td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit HPP -->
<div id="edit-hpp-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"></div>
        <div class="bg-black/40 backdrop-blur-md rounded-2xl p-6 max-w-md w-full z-10 shadow-2xl border border-white/20">
            <div class="flex justify-between items-center border-b border-white/10 pb-3 mb-4">
                <h3 class="text-white text-xl font-bold">Edit HPP</h3>
                <button type="button" onclick="closeEditHppModal()" class="text-gray-400 hover:text-white transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="edit-hpp-form" class="space-y-4 no-global-loading" data-no-global-loading="true">
                @csrf
                <input type="hidden" id="edit-hpp-id">
                <div>
                    <label class="block text-gray-200 text-sm font-medium mb-1">Ear Tag</label>
                    <p id="edit-hpp-ear-tag" class="text-white font-semibold">-</p>
                </div>
                <div>
                    <label class="block text-gray-200 text-sm font-medium mb-1">Harga Pembelian (Rp)</label>
                    <input type="number" step="1000" id="edit-hpp-purchase" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-gray-200 text-sm font-medium mb-1">Biaya Pakan (Rp)</label>
                    <input type="number" step="1000" id="edit-hpp-feed" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-gray-200 text-sm font-medium mb-1">Biaya Operasional (Rp)</label>
                    <input type="number" step="1000" id="edit-hpp-operational" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditHppModal()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-xl shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentEditHppId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadHppData();

    const editForm = document.getElementById('edit-hpp-form');
    if (editForm) {
        editForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            if (!currentEditHppId) {
                TernakPark.ui.showToast('ID HPP tidak valid', 'error');
                return;
            }

            const purchaseCost = document.getElementById('edit-hpp-purchase').value;
            const feedCost = document.getElementById('edit-hpp-feed').value;
            const operationalCost = document.getElementById('edit-hpp-operational').value;

            await updateHpp(currentEditHppId, purchaseCost, feedCost, operationalCost);
        });
    }
});

async function loadHppData() {
    try {
        const response = await TernakPark.api.fetchData('/web-api/hpp');
        if (response.success) {
            const data = response.data;
            document.getElementById('total-hpp-jantan').innerText = formatRupiah(data.total_hpp_jantan);
            document.getElementById('total-hpp-betina').innerText = formatRupiah(data.total_hpp_betina);
            document.getElementById('qty-jantan-breeding').innerText = data.qty_jantan_breeding;
            document.getElementById('total-hpp-jantan-breeding').innerText = formatRupiah(data.total_hpp_jantan_breeding);
            document.getElementById('qty-jantan-fattening').innerText = data.qty_jantan_fattening;
            document.getElementById('total-hpp-jantan-fattening').innerText = formatRupiah(data.total_hpp_jantan_fattening);
            document.getElementById('qty-betina').innerText = data.qty_betina;
            document.getElementById('total-hpp-betina-detail').innerText = formatRupiah(data.total_hpp_betina_detail);
            renderHppTable(data.detail);
        } else {
            TernakPark.ui.showToast(response.message || 'Gagal memuat data HPP', 'error');
        }
    } catch (e) {
        console.error(e);
        TernakPark.ui.showToast('Koneksi error: ' + e.message, 'error');
    }
}

function renderHppTable(detail) {
    const tbody = document.getElementById('hpp-table');
    if (!detail || detail.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-400">Tidak ada data<\/td><\/tr>';
        return;
    }
    let html = '';
    for (let i = 0; i < detail.length; i++) {
        const d = detail[i];
        html += `
            <tr class="border-b border-white/10 hover:bg-white/5 transition">
                <td class="px-6 py-3 font-medium">${escapeHtml(d.tagging)}<\/td>
                <td class="px-6 py-3">${formatRupiah(d.hpp_pembelian)}<\/td>
                <td class="px-6 py-3">${formatRupiah(d.pakan)}<\/td>
                <td class="px-6 py-3">${formatRupiah(d.operasional)}<\/td>
                <td class="px-6 py-3 text-emerald-300 font-semibold">${formatRupiah(d.total)}<\/td>
                <td class="px-6 py-3">
                    <button type="button" onclick="openEditHppModal(${d.id}, ${d.hpp_pembelian}, ${d.pakan}, ${d.operasional}, '${escapeHtml(d.tagging)}')" class="text-blue-400 hover:text-blue-300 transition">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                <\/td>
            <\/tr>
        `;
    }
    tbody.innerHTML = html;
}

function openEditHppModal(hppId, purchaseCost, feedCost, operationalCost, earTag) {
    currentEditHppId = hppId;
    document.getElementById('edit-hpp-ear-tag').innerText = earTag;
    document.getElementById('edit-hpp-purchase').value = purchaseCost;
    document.getElementById('edit-hpp-feed').value = feedCost;
    document.getElementById('edit-hpp-operational').value = operationalCost;
    document.getElementById('edit-hpp-modal').classList.remove('hidden');
}

function closeEditHppModal() {
    document.getElementById('edit-hpp-modal').classList.add('hidden');
    currentEditHppId = null;
}

async function updateHpp(hppId, purchaseCost, feedCost, operationalCost) {
    const submitBtn = document.querySelector('#edit-hpp-form button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const payload = {
            purchase_cost: parseFloat(purchaseCost) || 0,
            feed_cost: parseFloat(feedCost) || 0,
            operational_cost: parseFloat(operationalCost) || 0
        };
        console.log('Sending payload:', payload);

        const response = await fetch(`/web-api/hpp/${hppId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            let errorMsg = `HTTP ${response.status}`;
            try {
                const errorText = await response.text();
                if (errorText.includes('<!DOCTYPE')) {
                    errorMsg = 'Server mengembalikan halaman HTML. Mungkin endpoint tidak ditemukan atau session habis.';
                } else {
                    errorMsg = errorText.substring(0, 200);
                }
            } catch (ignored) {
                errorMsg = response.statusText;
            }
            throw new Error(errorMsg);
        }

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Response bukan JSON:', text.substring(0, 200));
            throw new Error('Server mengembalikan HTML. Mungkin endpoint tidak ditemukan atau session habis.');
        }

        const result = await response.json();
        if (result.success) {
            TernakPark.ui.showToast('Data HPP berhasil diperbarui', 'success');
            closeEditHppModal();
            await loadHppData();
        } else {
            TernakPark.ui.showToast(result.message || 'Gagal memperbarui data HPP', 'error');
        }
    } catch (error) {
        console.error('Update HPP error:', error);
        TernakPark.ui.showToast('Koneksi error: ' + error.message, 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

function formatRupiah(angka) {
    if (angka === undefined || angka === null) return 'Rp 0';
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
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