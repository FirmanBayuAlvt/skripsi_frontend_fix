<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\BackendApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HppController extends Controller
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
     * Menampilkan halaman utama HPP.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('hpp.index');
    }

    /**
     * Mengambil data HPP untuk AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHppData(Request $request)
    {
        try {
            $result = $this->api->getHppData();
            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error('Error fetching HPP data: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data HPP: ' . $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Memperbarui data HPP untuk satu ternak (pembelian, pakan, operasional).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $result = $this->api->updateHpp($id, $request->only(['purchase_cost', 'feed_cost', 'operational_cost']));
            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error('Error updating HPP: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data HPP: ' . $exception->getMessage()
            ], 500);
        }
    }
}