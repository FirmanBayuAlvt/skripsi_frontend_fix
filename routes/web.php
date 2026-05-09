<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LivestockController;
use App\Http\Controllers\Web\PenController;
use App\Http\Controllers\Web\FeedController;
use App\Http\Controllers\Web\PredictionController;
use App\Http\Controllers\Web\ChatbotController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\LogbookController;
use App\Http\Controllers\Web\HppController;
use App\Http\Controllers\Web\NotifikasiController;
use App\Http\Controllers\Web\ProgramController;

/*
|--------------------------------------------------------------------------
| Public Routes (Tidak Memerlukan Session Login)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Test koneksi ke backend
Route::get('/test-backend', function () {
    $url = config('services.backend.base_url') . '/test';
    try {
        $response = Http::timeout(5)->get($url);
        return response()->json(['success' => true, 'status' => $response->status()]);
    } catch (\Exception $exception) {
        return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
    }
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Memerlukan Session Login - Dicek di Controller)
|--------------------------------------------------------------------------
*/

// Dashboard (semua role)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Prediksi (semua role)
Route::prefix('predictions')->name('predictions.')->group(function () {
    Route::get('/', [PredictionController::class, 'index'])->name('index');
    Route::get('/correlation', [PredictionController::class, 'correlation'])->name('correlation');
});

// Chatbot (public endpoint)
Route::post('/api/chatbot-public', [ChatbotController::class, 'handle'])->name('chatbot.public');

// Manajemen Ternak (hanya Administrator, dicek di controller)
Route::prefix('livestocks')->name('livestocks.')->group(function () {
    Route::get('/', [LivestockController::class, 'index'])->name('index');
    Route::get('/{id}', [LivestockController::class, 'show'])->where('id', '[0-9]+')->name('show');
});

// Manajemen Kandang
Route::prefix('pens')->name('pens.')->group(function () {
    Route::get('/', [PenController::class, 'index'])->name('index');
    Route::get('/{id}', [PenController::class, 'show'])->where('id', '[0-9]+')->name('show');
    Route::get('/{id}/analytics', [PenController::class, 'analytics'])->where('id', '[0-9]+')->name('analytics');
});

// Manajemen Pakan (Halaman View)
Route::prefix('feeds')->name('feeds.')->group(function () {
    Route::get('/', [FeedController::class, 'index'])->name('index');
    Route::get('/stock', [FeedController::class, 'stock'])->name('stock');
    Route::get('/requirements', [FeedController::class, 'requirements'])->name('requirements');
    Route::get('/usage', [FeedController::class, 'usage'])->name('usage');
    Route::get('/procurement', [FeedController::class, 'procurement'])->name('procurement');
    Route::get('/analytics', [FeedController::class, 'analytics'])->name('analytics');
});

// Laporan
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/performance', [ReportController::class, 'performance'])->name('performance');
    Route::get('/growth', [ReportController::class, 'growth'])->name('growth');
});

// Halaman Statis (General Manager & Admin)
Route::get('/logbook', [LogbookController::class, 'index'])->name('logbook.index');
Route::get('/hpp', [HppController::class, 'index'])->name('hpp.index');
Route::get('/notifikasi-harian', [NotifikasiController::class, 'index'])->name('notifikasi.index');

// Program Fattening & Breeding (Halaman View)
Route::get('/fattening-domba', [ProgramController::class, 'fattening'])->name('program.fattening');
Route::get('/breeding-domba', [ProgramController::class, 'breeding'])->name('program.breeding');

// Halaman Detail untuk Fattening
Route::get('/fattening-domba/detail', [ProgramController::class, 'fatteningDetail'])->name('program.fattening.detail');
Route::get('/fattening-domba/timbang', [ProgramController::class, 'fatteningTimbang'])->name('program.fattening.timbang');
Route::get('/fattening-domba/adg-fcr', [ProgramController::class, 'fatteningAdgFcr'])->name('program.fattening.adg-fcr');

// Halaman Detail untuk Breeding
Route::get('/breeding-domba/indukan', [ProgramController::class, 'breedingIndukan'])->name('program.breeding.indukan');
Route::get('/breeding-domba/pejantan', [ProgramController::class, 'breedingPejantan'])->name('program.breeding.pejantan');
Route::get('/breeding-domba/anakan', [ProgramController::class, 'breedingAnakan'])->name('program.breeding.anakan');
Route::get('/breeding-domba/kawin-ib', [ProgramController::class, 'breedingKawinIb'])->name('program.breeding.kawin-ib');

/*
|--------------------------------------------------------------------------
| Web API (AJAX) – Endpoint untuk mengambil/mengirim data JSON
|--------------------------------------------------------------------------
*/

Route::prefix('web-api')->name('web-api.')->group(function () {

    // Dashboard API
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/overview', [DashboardController::class, 'getOverviewData'])->name('overview');
        Route::get('/pen-analytics', [DashboardController::class, 'getPenAnalytics'])->name('pen-analytics');
        Route::get('/predictions/history', [DashboardController::class, 'getPredictionHistory'])->name('predictions.history');
        Route::get('/statistics', [DashboardController::class, 'getStatistics'])->name('statistics');
    });

    // Prediksi API
    Route::prefix('predictions')->name('predictions.')->group(function () {
        Route::get('/data', [PredictionController::class, 'getPredictionsData'])->name('data');
        Route::get('/history', [PredictionController::class, 'getPredictionHistory'])->name('history');
        Route::get('/correlation', [PredictionController::class, 'getCorrelationData'])->name('correlation');
        Route::post('/create', [PredictionController::class, 'createPrediction'])->name('create');
    });

    // Report API
    Route::get('/reports/data', [ReportController::class, 'getReportsData'])->name('reports.data');

    // Program API (Fattening & Breeding)
    Route::get('/program/fattening', [ProgramController::class, 'getFatteningData'])->name('program.fattening');
    Route::get('/program/fattening-detailed', [ProgramController::class, 'getFatteningDetailedData'])->name('program.fattening-detailed');
    Route::get('/program/breeding', [ProgramController::class, 'getBreedingData'])->name('program.breeding');
    Route::get('/program/family', [ProgramController::class, 'getFamily'])->name('program.family');
    Route::get('/program/fattening-timbang', [ProgramController::class, 'getFatteningTimbangData'])->name('program.fattening-timbang');
    Route::get('/program/fattening-adg-fcr', [ProgramController::class, 'getFatteningAdgFcrData'])->name('program.fattening-adg-fcr');

    // Breeding Sub Modules API
    Route::get('/program/breeding/induk', [ProgramController::class, 'getBreedingIndukData'])->name('program.breeding.induk');
    Route::get('/program/breeding/jantan', [ProgramController::class, 'getBreedingJantanData'])->name('program.breeding.jantan');
    Route::get('/program/breeding/anakan', [ProgramController::class, 'getBreedingAnakanData'])->name('program.breeding.anakan');
    Route::get('/program/breeding/indukan', [ProgramController::class, 'getBreedingIndukanData'])->name('program.breeding.indukan');
    Route::get('/program/breeding/pejantan', [ProgramController::class, 'getBreedingPejantanData'])->name('program.breeding.pejantan');
    Route::get('/program/breeding/kawin-ib', [ProgramController::class, 'getBreedingKawinIbData'])->name('program.breeding.kawin-ib');
    Route::get('/program/breeding/indukan/detail', [ProgramController::class, 'breedingIndukanDetail'])->name('program.breeding.indukan.detail');

    // Livestock API (CRUD + Data)
    Route::prefix('livestocks')->name('livestocks.')->group(function () {
        Route::get('/data', [LivestockController::class, 'getLivestocksData'])->name('data');
        Route::get('/{id}/detail', [LivestockController::class, 'getLivestockDetail'])->where('id', '[0-9]+')->name('detail');
        Route::post('/store', [LivestockController::class, 'storeLivestock'])->name('store');
        Route::put('/{id}/update', [LivestockController::class, 'updateLivestock'])->where('id', '[0-9]+')->name('update');
        Route::delete('/{id}', [LivestockController::class, 'destroyLivestock'])->name('destroy');
        Route::post('/{id}/record-weight', [LivestockController::class, 'recordWeight'])->where('id', '[0-9]+')->name('record-weight');
        Route::post('/import', [LivestockController::class, 'importForward'])->name('import');
    });

    // Pen API
    Route::prefix('pens')->name('pens.')->group(function () {
        Route::get('/data', [PenController::class, 'getPensData'])->name('data');
        Route::get('/{id}/detail', [PenController::class, 'getPenDetail'])->where('id', '[0-9]+')->name('detail');
        Route::get('/{id}/analytics', [PenController::class, 'getPenAnalytics'])->where('id', '[0-9]+')->name('analytics');
        Route::post('/store', [PenController::class, 'storePen'])->name('store');
        Route::put('/{id}/update', [PenController::class, 'updatePen'])->where('id', '[0-9]+')->name('update');
        Route::delete('/{id}', [PenController::class, 'destroyPen'])->name('destroy');
        Route::post('/import', [PenController::class, 'importForward'])->name('import');
        Route::get('/livestock', [PenController::class, 'getPenSummary'])->name('livestock');
    });

    // Feed API (CRUD lengkap + usage & procurement)
    Route::prefix('feeds')->name('feeds.')->group(function () {
        Route::get('/data', [FeedController::class, 'getFeedsData'])->name('data');
        Route::get('/stock-levels', [FeedController::class, 'getStockLevels'])->name('stock-levels');
        Route::get('/requirements', [FeedController::class, 'getFeedRequirements'])->name('requirements');
        Route::post('/record-feeding', [FeedController::class, 'recordFeeding'])->name('record-feeding');
        Route::post('/update-stock', [FeedController::class, 'updateStock'])->name('update-stock');
        Route::post('/store', [FeedController::class, 'storeFeed'])->name('store');
        Route::put('/update/{id}', [FeedController::class, 'updateFeed'])->where('id', '[0-9]+')->name('update');
        Route::delete('/delete/{id}', [FeedController::class, 'destroyFeed'])->where('id', '[0-9]+')->name('delete');
        Route::post('/import', [FeedController::class, 'importFeeds'])->name('import');
        Route::get('/analytics', [FeedController::class, 'getAnalytics'])->name('analytics');
        Route::get('/usage-data', [FeedController::class, 'getUsageData'])->name('usage-data');
        Route::post('/feeding-record', [FeedController::class, 'storeFeedingRecord'])->name('feeding-record');
        Route::post('/purchase-record', [FeedController::class, 'storeFeedPurchase'])->name('purchase-record');
        Route::get('/procurement-data', [FeedController::class, 'getProcurementData'])->name('procurement-data');
    });

    // Logbook API
    Route::get('/logbook', [LogbookController::class, 'getLogbookData'])->name('logbook.data');

    // HPP API
    Route::get('/hpp', [HppController::class, 'getHppData'])->name('hpp.data');
    Route::put('/hpp/{id}', [HppController::class, 'update'])->name('hpp.update');

    // Notifikasi API
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [NotifikasiController::class, 'getNotifications'])->name('get');
        Route::get('/unread-count', [NotifikasiController::class, 'getUnreadCount'])->name('unread');
        Route::post('/{id}/mark-as-read', [NotifikasiController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-as-read', [NotifikasiController::class, 'markAllAsRead'])->name('mark-all-read');
    });

    // ==================== PROXY ENDPOINT UNTUK KAWIN & IB ====================
    // Menggunakan method publik (tanpa token) untuk mengatasi masalah autentikasi
    Route::post('/program/breeding/kawin-ib/store', [ProgramController::class, 'storeKawinIbPublic'])
        ->name('program.breeding.kawin-ib.store');
    Route::get('/program/breeding/kawin-ib/detail', [ProgramController::class, 'detailKawinIbPublic'])
        ->name('program.breeding.kawin-ib.detail');
});

/*
|--------------------------------------------------------------------------
| Fallback – Halaman 404 jika route tidak ditemukan
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return view('errors.404');
});