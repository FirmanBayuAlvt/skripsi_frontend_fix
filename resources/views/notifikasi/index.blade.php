@extends('layouts.app')

@section('title', 'Notifikasi')
@section('header-title', 'Notifikasi')

@section('content')
<div class="space-y-6">
    <!-- Tombol Kembali ke Dashboard -->
    <div class="flex justify-end">
        <a href="{{ route('dashboard') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- Kartu Notifikasi Stok Pakan & Kesehatan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Stok Pakan Menipis -->
        <div class="glass-card p-5">
            <div class="flex items-center gap-3 border-b border-white/10 pb-3 mb-4">
                <div class="p-2 bg-amber-500/20 rounded-xl">
                    <i class="fas fa-exclamation-triangle text-amber-400 text-xl"></i>
                </div>
                <h3 class="font-bold text-white text-lg">Stok Pakan Menipis</h3>
            </div>
            <div id="low-stock-notif" class="text-gray-300">
                <div class="text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat...</div>
            </div>
        </div>

        <!-- Peringatan Kesehatan -->
        <div class="glass-card p-5">
            <div class="flex items-center gap-3 border-b border-white/10 pb-3 mb-4">
                <div class="p-2 bg-red-500/20 rounded-xl">
                    <i class="fas fa-heartbeat text-red-400 text-xl"></i>
                </div>
                <h3 class="font-bold text-white text-lg">Peringatan Kesehatan</h3>
            </div>
            <div id="health-notif" class="text-gray-300">
                <div class="text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat...</div>
            </div>
        </div>
    </div>

    {{-- Ringkasan HPP & Rekomendasi --}}
    <div class="glass-card p-0 overflow-hidden">
        <div class="flex items-center gap-2 border-b border-white/10 p-5">
            <i class="fas fa-chart-line text-emerald-400 text-xl"></i>
            <h3 class="font-bold text-white text-lg">Ringkasan HPP & Rekomendasi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="custom-table w-full text-sm text-white">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr class="text-gray-300 text-xs uppercase">
                        <th class="px-4 py-3 text-left">Tagging</th>
                        <th class="px-4 py-3 text-left">HPP Pembelian</th>
                        <th class="px-4 py-3 text-left">Pakan</th>
                        <th class="px-4 py-3 text-left">Operasional</th>
                        <th class="px-4 py-3 text-left">Total HPP</th>
                        <th class="px-4 py-3 text-left">Rekomendasi</th>
                    </tr>
                </thead>
                <tbody id="hpp-notif-table">
                    <tr><td colspan="6" class="text-center py-8 text-gray-400">Memuat data...<\/td><\/tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Semua Notifikasi --}}
    <div class="glass-card p-0 overflow-hidden">
        <div class="flex items-center justify-between border-b border-white/10 p-5">
            <div class="flex items-center gap-2">
                <i class="fas fa-bell text-emerald-400 text-xl"></i>
                <h3 class="font-bold text-white text-lg">Semua Notifikasi</h3>
            </div>
            <button id="mark-all-read-btn" class="text-sm text-emerald-400 hover:text-emerald-300 transition">Tandai semua telah dibaca</button>
        </div>
        <div id="notifications-list" class="divide-y divide-white/10">
            <div class="p-8 text-center text-gray-400">Memuat...</div>
        </div>
        <div id="pagination" class="p-4 border-t border-white/10 flex justify-between items-center"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;

    document.addEventListener('DOMContentLoaded', function() {
        loadNotifikasiRingkasan();
        loadNotifications(currentPage);
        const markAllButton = document.getElementById('mark-all-read-btn');
        if (markAllButton) {
            markAllButton.addEventListener('click', markAllAsRead);
        }
    });

    // ==================== FUNGSI REKOMENDASI HPP ====================
    function getRekomendasiHpp(hppData) {
        if (hppData.total > 3000000) {
            return 'Biaya tinggi, perlu efisiensi';
        }
        if (hppData.pakan === 0) {
            return 'Catat konsumsi pakan';
        }
        if (hppData.operasional === 0) {
            return 'Tambahkan biaya operasional';
        }
        return '-';
    }

    // ==================== RINGKASAN (STOK, KESEHATAN, HPP) ====================
    async function loadNotifikasiRingkasan() {
        try {
            // 1. Stok pakan menipis
            const feedStock = await TernakPark.api.fetchData('/web-api/feeds/stock-levels');
            if (feedStock.success && feedStock.data.low_stock_alerts) {
                renderLowStockFeeds(feedStock.data.low_stock_alerts);
            } else {
                renderLowStockFeeds([]);
            }

            // 2. Peringatan kesehatan (fair/poor)
            const livestocks = await TernakPark.api.fetchData('/web-api/livestocks/data?per_page=1000');
            if (livestocks.success) {
                const healthWarnings = livestocks.data.livestocks.filter(function(l) {
                    return l.health_status === 'poor' || l.health_status === 'fair';
                });
                renderHealthWarnings(healthWarnings);
            } else {
                renderHealthWarnings([]);
            }

            // 3. Data HPP (ringkasan) dengan rekomendasi
            const hppData = await TernakPark.api.fetchData('/web-api/hpp');
            if (hppData.success && hppData.data.detail) {
                renderHppTable(hppData.data.detail);
            } else {
                renderHppTable([]);
            }
        } catch (error) {
            console.error(error);
            TernakPark.ui.showToast('Gagal memuat ringkasan notifikasi', 'error');
        }
    }

    function renderLowStockFeeds(feeds) {
        const container = document.getElementById('low-stock-notif');
        if (!feeds || feeds.length === 0) {
            container.innerHTML = '<div class="flex items-center gap-3 p-3 bg-emerald-500/10 rounded-xl border border-emerald-500/30"><i class="fas fa-check-circle text-emerald-400"></i><span>Tidak ada stok pakan menipis.</span></div>';
            return;
        }
        let html = '<div class="space-y-2">';
        for (let i = 0; i < feeds.length; i++) {
            const feed = feeds[i];
            html += `
                <div class="flex justify-between items-center p-3 bg-amber-500/10 rounded-xl border border-amber-500/30">
                    <div>
                        <p class="font-medium text-white">${escapeHtml(feed.name)}</p>
                        <p class="text-sm text-gray-400">Stok tersisa</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-amber-400">${feed.current_stock} kg</p>
                        <p class="text-xs text-gray-400">Segera restok</p>
                    </div>
                </div>
            `;
        }
        html += '</div>';
        container.innerHTML = html;
    }

    function renderHealthWarnings(warnings) {
        const container = document.getElementById('health-notif');
        if (!warnings || warnings.length === 0) {
            container.innerHTML = '<div class="flex items-center gap-3 p-3 bg-emerald-500/10 rounded-xl border border-emerald-500/30"><i class="fas fa-check-circle text-emerald-400"></i><span>Tidak ada peringatan kesehatan.</span></div>';
            return;
        }
        let html = '<div class="space-y-2">';
        for (let i = 0; i < warnings.length; i++) {
            const w = warnings[i];
            html += `
                <div class="flex justify-between items-center p-3 bg-red-500/10 rounded-xl border border-red-500/30">
                    <div>
                        <p class="font-medium text-white">${escapeHtml(w.ear_tag)}</p>
                        <p class="text-sm text-gray-400">Status kesehatan: ${escapeHtml(w.health_status)}</p>
                    </div>
                    <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                </div>
            `;
        }
        html += '</div>';
        container.innerHTML = html;
    }

    function renderHppTable(detail) {
        const tbody = document.getElementById('hpp-notif-table');
        if (!detail || detail.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-400">Tidak ada data HPP</td></tr>';
            return;
        }
        let html = '';
        for (let i = 0; i < detail.length; i++) {
            const d = detail[i];
            const rekomendasi = getRekomendasiHpp({
                total: d.total,
                pakan: d.pakan,
                operasional: d.operasional
            });
            html += `
                <tr class="border-b border-white/10 hover:bg-white/5 transition">
                    <td class="px-4 py-3 font-medium">${escapeHtml(d.tagging)}<\/td>
                    <td class="px-4 py-3">${formatRupiah(d.hpp_pembelian)}<\/td>
                    <td class="px-4 py-3">${formatRupiah(d.pakan)}<\/td>
                    <td class="px-4 py-3">${formatRupiah(d.operasional)}<\/td>
                    <td class="px-4 py-3 text-emerald-300 font-semibold">${formatRupiah(d.total)}<\/td>
                    <td class="px-4 py-3">${escapeHtml(rekomendasi)}<\/td>
                <\/tr>
            `;
        }
        tbody.innerHTML = html;
    }

    // ==================== SEMUA NOTIFIKASI (dari tabel notifications) ====================
    async function loadNotifications(pageNumber = 1) {
        currentPage = pageNumber;
        try {
            const response = await TernakPark.api.fetchData(`/web-api/notifikasi?page=${pageNumber}&per_page=15`);
            if (response.success) {
                renderAllNotifications(response.data);
                renderPaginationAll(response.pagination);
            } else {
                document.getElementById('notifications-list').innerHTML = '<div class="p-8 text-center text-red-400">Gagal memuat notifikasi</div>';
            }
        } catch (error) {
            console.error(error);
            document.getElementById('notifications-list').innerHTML = '<div class="p-8 text-center text-red-400">Koneksi error</div>';
        }
    }

    function renderAllNotifications(notifications) {
        const container = document.getElementById('notifications-list');
        if (!notifications || notifications.length === 0) {
            container.innerHTML = '<div class="p-8 text-center text-gray-400">Tidak ada notifikasi</div>';
            return;
        }
        let html = '';
        for (let i = 0; i < notifications.length; i++) {
            const n = notifications[i];
            const isUnread = n.read_at === null;
            html += `
                <div class="p-4 hover:bg-white/5 transition cursor-pointer ${isUnread ? 'bg-emerald-900/20' : ''}" data-id="${n.id}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white">${escapeHtml(n.title)}</p>
                            <p class="text-xs text-gray-400 mt-1">${escapeHtml(n.message)}</p>
                            <p class="text-xs text-gray-500 mt-2">${TernakPark.format.timeAgo(n.created_at)}</p>
                        </div>
                        ${isUnread ? '<i class="fas fa-circle text-emerald-500 text-xs mt-1 ml-2"></i>' : ''}
                    </div>
                </div>
            `;
        }
        container.innerHTML = html;

        // Pasang event listener untuk menandai sudah dibaca saat diklik
        const elements = container.querySelectorAll('[data-id]');
        for (let i = 0; i < elements.length; i++) {
            const el = elements[i];
            el.addEventListener('click', function() {
                const notificationId = this.getAttribute('data-id');
                markSingleNotificationAsRead(notificationId);
            });
        }
    }

    function renderPaginationAll(pagination) {
        const container = document.getElementById('pagination');
        if (!pagination || pagination.total <= pagination.per_page) {
            container.innerHTML = '';
            return;
        }
        const start = (pagination.current_page - 1) * pagination.per_page + 1;
        const end = Math.min(pagination.current_page * pagination.per_page, pagination.total);
        let html = `<div class="text-sm text-gray-400">Menampilkan ${start} - ${end} dari ${pagination.total}</div>`;
        html += '<div class="flex space-x-2">';
        if (pagination.current_page > 1) {
            html += `<button onclick="loadNotifications(${pagination.current_page - 1})" class="px-3 py-1 bg-white/10 rounded-xl hover:bg-white/20">Prev</button>`;
        }
        if (pagination.current_page < pagination.last_page) {
            html += `<button onclick="loadNotifications(${pagination.current_page + 1})" class="px-3 py-1 bg-white/10 rounded-xl hover:bg-white/20">Next</button>`;
        }
        html += '</div>';
        container.innerHTML = html;
    }

    async function markSingleNotificationAsRead(notificationId) {
        try {
            const response = await fetch(`/web-api/notifikasi/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const result = await response.json();
            if (result.success) {
                loadNotifications(currentPage);
                updateUnreadBadge();
            }
        } catch (error) {
            console.error('Failed to mark notification as read:', error);
        }
    }

    async function markAllAsRead() {
        try {
            const response = await fetch('/web-api/notifikasi/mark-all-as-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const result = await response.json();
            if (result.success) {
                loadNotifications(currentPage);
                updateUnreadBadge();
                TernakPark.ui.showToast('Semua notifikasi telah ditandai dibaca', 'success');
            }
        } catch (error) {
            console.error(error);
            TernakPark.ui.showToast('Gagal menandai semua notifikasi', 'error');
        }
    }

    async function updateUnreadBadge() {
        try {
            const response = await fetch('/web-api/notifikasi/unread-count');
            const data = await response.json();
            if (data.success && typeof window.updateNotificationBadge === 'function') {
                window.updateNotificationBadge(data.count);
            }
        } catch (error) {
            console.error('Failed to fetch unread count for badge:', error);
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