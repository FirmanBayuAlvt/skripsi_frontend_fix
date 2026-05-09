<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\BackendApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LivestockController extends Controller
{
    /**
     * Instance dari BackendApiService untuk komunikasi dengan backend API.
     *
     * @var \App\Services\BackendApiService
     */
    protected BackendApiService $api;

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
     * Menampilkan halaman utama manajemen ternak.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if (!session()->has('user')) {
            return redirect()->route('login');
        }
        if (session('role') !== 'administrator') {
            abort(403, 'Akses ditolak.');
        }
        return view('livestocks.index');
    }

    /**
     * Menampilkan detail satu ternak.
     *
     * @param  int|string  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $response = $this->api->getLivestockDetail($id);
        if (!($response['success'] ?? false)) {
            abort(404);
        }
        return view('livestocks.show', ['livestock' => $response['data']]);
    }

    /**
     * Mengambil data ternak untuk datatable (AJAX).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLivestocksData(Request $request)
    {
        try {
            $result = $this->api->getLivestocks($request->all());
            return response()->json($result, $this->getHttpStatusCode($result));
        } catch (\Exception $exception) {
            Log::error('Error fetching livestocks data: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data ternak.'
            ], 500);
        }
    }

    /**
     * Mengambil detail satu ternak (AJAX).
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLivestockDetail($id)
    {
        try {
            $result = $this->api->getLivestockDetail($id);
            return response()->json($result, $this->getHttpStatusCode($result));
        } catch (\Exception $exception) {
            Log::error('Error fetching livestock detail: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail ternak.'
            ], 500);
        }
    }

    /**
     * Menyimpan data ternak baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeLivestock(Request $request)
    {
        try {
            $result = $this->api->createLivestock($request->all());
            return response()->json($result, $this->getHttpStatusCode($result));
        } catch (\Exception $exception) {
            Log::error('Error storing livestock: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data ternak.'
            ], 500);
        }
    }

    /**
     * Memperbarui data ternak yang sudah ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLivestock(Request $request, $id)
    {
        try {
            $data = $request->all();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }

            $result = $this->api->updateLivestock($id, $data);
            return response()->json($result, $this->getHttpStatusCode($result));
        } catch (\Exception $exception) {
            Log::error('Error updating livestock: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data ternak.'
            ], 500);
        }
    }

    /**
     * Menonaktifkan (soft delete) ternak.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyLivestock($id)
    {
        try {
            $result = $this->api->deleteLivestock($id);
            return response()->json($result, $this->getHttpStatusCode($result));
        } catch (\Exception $exception) {
            Log::error('Error deleting livestock: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menonaktifkan ternak.'
            ], 500);
        }
    }

    /**
     * Mencatat berat badan ternak.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function recordWeight(Request $request, $id)
    {
        try {
            $result = $this->api->recordWeight($id, $request->all());
            return response()->json($result, $this->getHttpStatusCode($result));
        } catch (\Exception $exception) {
            Log::error('Error recording weight: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat berat badan.'
            ], 500);
        }
    }

    /**
     * Mengimpor data ternak dari file Excel (forward ke backend API).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importForward(Request $request)
    {
        try {
            $token = session('token');
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak ditemukan. Silakan login kembali.'
                ], 401);
            }

            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv'
            ]);

            $backendUrl = rtrim(config('services.backend.base_url'), '/');
            $file = $request->file('file');

            $response = Http::withToken($token)
                ->timeout(60)
                ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                ->post($backendUrl . '/livestocks/import');

            return response()->json($response->json(), $response->status());
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Import forward error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan status HTTP berdasarkan respons dari service.
     *
     * @param  array  $result
     * @return int
     */
    private function getHttpStatusCode(array $result): int
    {
        if (($result['success'] ?? false) === true) {
            return 200;
        }
        return $result['status'] ?? 400;
    }
}