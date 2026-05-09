<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\BackendApiService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Instance dari BackendApiService untuk komunikasi dengan backend API.
     *
     * @var \App\Services\BackendApiService
     */
    protected $api;

    /**
     * Constructor untuk menginisialisasi service API.
     *
     * @param \App\Services\BackendApiService $api
     */
    public function __construct(BackendApiService $api)
    {
        $this->api = $api;
    }

    /**
     * Menampilkan halaman dashboard utama.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Mengambil data overview untuk dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOverviewData()
    {
        return response()->json($this->api->getDashboardOverview());
    }

    /**
     * Mengambil data analitik kandang untuk dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPenAnalytics()
    {
        return response()->json($this->api->getDashboardPenAnalytics());
    }

    /**
     * Mengambil riwayat prediksi untuk dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPredictionHistory()
    {
        return response()->json($this->api->getPredictionHistory(['per_page' => 5]));
    }

    /**
     * Mengambil data statistik lengkap untuk dashboard (jumlah ternak, subkategori, breed, gender, dll).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistics()
    {
        return response()->json($this->api->getDashboardStatistics());
    }
}