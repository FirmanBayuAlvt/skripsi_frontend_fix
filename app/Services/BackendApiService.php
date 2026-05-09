<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class BackendApiService
{
    /**
     * Base URL dari backend API.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Timeout request dalam detik.
     *
     * @var int
     */
    protected $timeout = 60;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->baseUrl = config('services.backend.base_url');
        Log::info('BackendApiService initialized', ['base_url' => $this->baseUrl]);
    }

    /**
     * Melakukan request HTTP ke backend API.
     * Method ini dibuat public agar dapat dipanggil dari controller lain.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function request(string $method, string $endpoint, array $data = []): array
    {
        try {
            // Daftar endpoint yang boleh diakses tanpa token
            $publicEndpoints = [
                'program/breeding/kawin',
                'program/breeding/kawin-ib',
            ];

            $normalizedEndpoint = ltrim($endpoint, '/');
            $isPublic = false;
            foreach ($publicEndpoints as $public) {
                if (strpos($normalizedEndpoint, $public) === 0) {
                    $isPublic = true;
                    break;
                }
            }

            $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
            $http = Http::timeout($this->timeout)->withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ]);

            if (!$isPublic) {
                $token = session('token');
                if (empty($token)) {
                    Log::error('BackendApiService: No token for private endpoint: ' . $endpoint);
                    return [
                        'success'  => false,
                        'message'  => 'Token tidak ditemukan. Silakan login kembali.',
                        'redirect' => true,
                    ];
                }
                $http = $http->withToken($token);
                Log::info('BackendApiService request (private)', [
                    'method' => $method,
                    'url'    => $url,
                ]);
            } else {
                Log::info('BackendApiService request (public)', [
                    'method' => $method,
                    'url'    => $url,
                ]);
            }

            $response = $http->$method($url, $data);

            // Jika status 401 dan bukan public, flush session
            if ($response->status() === 401 && !$isPublic) {
                session()->flush();
                Log::error('Backend API 401 Unauthorized - session flushed');
                return [
                    'success'  => false,
                    'message'  => 'Sesi login habis. Silakan refresh halaman dan login kembali.',
                    'redirect' => true,
                ];
            }

            // Validasi response JSON
            $contentType = $response->header('Content-Type');
            if (!str_contains($contentType, 'application/json')) {
                $bodyPreview = substr($response->body(), 0, 500);
                Log::error('Backend API response not JSON', [
                    'url'          => $url,
                    'status'       => $response->status(),
                    'content_type' => $contentType,
                    'body_preview' => $bodyPreview,
                ]);

                if (in_array($response->status(), [419, 500])) {
                    session()->flush();
                }

                return [
                    'success' => false,
                    'message' => 'Server backend mengembalikan format tidak dikenal (bukan JSON). Detail: ' . $bodyPreview,
                    'status'  => $response->status(),
                ];
            }

            $responseData = $response->json();

            if ($response->successful()) {
                return $responseData;
            }

            $errorMessage = 'Backend API error: ' . $response->status();
            if (isset($responseData['message'])) {
                $errorMessage = $responseData['message'];
            } elseif (isset($responseData['errors'])) {
                $errorMessage = implode(', ', (array) $responseData['errors']);
            }

            Log::error('Backend API error', [
                'method' => $method,
                'url'    => $url,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => $errorMessage,
                'status'  => $response->status(),
            ];
        } catch (\Exception $exception) {
            Log::error('Backend API exception', [
                'message' => $exception->getMessage(),
                'trace'   => $exception->getTraceAsString(),
            ]);
            return [
                'success' => false,
                'message' => 'Tidak dapat terhubung ke server backend: ' . $exception->getMessage(),
            ];
        }
    }

    /**
     * Mengirim request upload file (multipart/form-data).
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @param string $fileKey
     * @param array $extraFields
     * @return array
     */
    protected function uploadFileRequest(string $method, string $endpoint, array $data, string $fileKey, array $extraFields = []): array
    {
        try {
            $token = session('token');
            if (empty($token)) {
                Log::warning('Upload request tanpa token');
                return [
                    'success'  => false,
                    'message'  => 'Token tidak ditemukan. Silakan login kembali.',
                    'redirect' => true,
                ];
            }

            $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
            $http = Http::timeout($this->timeout)
                ->withToken($token)
                ->withHeaders(['Accept' => 'application/json']);

            $file = $data[$fileKey] ?? null;
            unset($data[$fileKey]);

            foreach ($extraFields as $key => $value) {
                $data[$key] = $value;
            }

            $response = $http->attach(
                $fileKey,
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post($url, $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Backend API upload error', [
                'url'    => $url,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Upload gagal: ' . $response->status(),
            ];
        } catch (\Exception $exception) {
            Log::error('Backend API upload exception', ['message' => $exception->getMessage()]);
            return [
                'success' => false,
                'message' => 'Koneksi error: ' . $exception->getMessage(),
            ];
        }
    }

    // ==================== LIVESTOCKS ====================

    /**
     * Mendapatkan daftar ternak.
     *
     * @param array $params
     * @return array
     */
    public function getLivestocks(array $params = []): array
    {
        return $this->request('get', '/livestocks', $params);
    }

    /**
     * Mendapatkan detail satu ternak.
     *
     * @param int|string $id
     * @return array
     */
    public function getLivestockDetail($id): array
    {
        return $this->request('get', "/livestocks/{$id}");
    }

    /**
     * Menambahkan ternak baru.
     *
     * @param array $data
     * @return array
     */
    public function createLivestock(array $data): array
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            return $this->uploadFileRequest('post', '/livestocks', $data, 'image');
        }
        return $this->request('post', '/livestocks', $data);
    }

    /**
     * Memperbarui data ternak.
     *
     * @param int|string $id
     * @param array $data
     * @return array
     */
    public function updateLivestock($id, array $data): array
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            return $this->uploadFileRequest('post', "/livestocks/{$id}", $data, 'image', ['_method' => 'PUT']);
        }
        return $this->request('put', "/livestocks/{$id}", $data);
    }

    /**
     * Menghapus ternak.
     *
     * @param int|string $id
     * @return array
     */
    public function deleteLivestock($id): array
    {
        return $this->request('delete', "/livestocks/{$id}");
    }

    /**
     * Mencatat berat badan ternak.
     *
     * @param int|string $id
     * @param array $data
     * @return array
     */
    public function recordWeight($id, array $data): array
    {
        return $this->request('post', "/livestocks/{$id}/record-weight", $data);
    }

    /**
     * Mengimpor data ternak dari file Excel.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public function importLivestocks(UploadedFile $file): array
    {
        return $this->uploadFileRequest('post', '/livestocks/import', ['file' => $file], 'file');
    }

    // ==================== PENS ====================

    /**
     * Mendapatkan daftar kandang.
     *
     * @param array $params
     * @return array
     */
    public function getPens(array $params = []): array
    {
        return $this->request('get', '/pens', $params);
    }

    /**
     * Mendapatkan detail satu kandang.
     *
     * @param int|string $id
     * @return array
     */
    public function getPenDetail($id): array
    {
        return $this->request('get', "/pens/{$id}");
    }

    /**
     * Mendapatkan data analitik kandang.
     *
     * @param int|string $id
     * @return array
     */
    public function getPenAnalytics($id): array
    {
        return $this->request('get', "/pens/{$id}/analytics");
    }

    /**
     * Menambahkan kandang baru.
     *
     * @param array $data
     * @return array
     */
    public function createPen(array $data): array
    {
        return $this->request('post', '/pens', $data);
    }

    /**
     * Memperbarui data kandang.
     *
     * @param int|string $id
     * @param array $data
     * @return array
     */
    public function updatePen($id, array $data): array
    {
        return $this->request('put', "/pens/{$id}", $data);
    }

    /**
     * Menghapus kandang.
     *
     * @param int|string $id
     * @return array
     */
    public function deletePen($id): array
    {
        return $this->request('delete', "/pens/{$id}");
    }

    /**
     * Mengimpor data kandang dari file Excel.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public function importPens(UploadedFile $file): array
    {
        return $this->uploadFileRequest('post', '/pens/import', ['file' => $file], 'file');
    }

    /**
     * Mendapatkan ringkasan ternak per kandang.
     *
     * @return array
     */
    public function getPenSummary(): array
    {
        return $this->request('get', '/pens/livestock');
    }

    // ==================== FEEDS ====================

    /**
     * Mendapatkan daftar pakan.
     *
     * @param array $params
     * @return array
     */
    public function getFeeds(array $params = []): array
    {
        return $this->request('get', '/feeds', $params);
    }

    /**
     * Mendapatkan ringkasan stok pakan.
     *
     * @return array
     */
    public function getFeedStock(): array
    {
        return $this->request('get', '/feeds/stock/summary');
    }

    /**
     * Mendapatkan kebutuhan pakan.
     *
     * @return array
     */
    public function getFeedRequirements(): array
    {
        return $this->request('get', '/feeds/requirements');
    }

    /**
     * Mencatat pemberian pakan.
     *
     * @param array $data
     * @return array
     */
    public function recordFeeding(array $data): array
    {
        return $this->request('post', '/feeds/record-feeding', $data);
    }

    /**
     * Memperbarui stok pakan.
     *
     * @param array $data
     * @return array
     */
    public function updateFeedStock(array $data): array
    {
        return $this->request('post', '/feeds/update-stock', $data);
    }

    /**
     * Menambahkan jenis pakan baru.
     *
     * @param array $data
     * @return array
     */
    public function createFeed(array $data): array
    {
        return $this->request('post', '/feeds', $data);
    }

    /**
     * Memperbarui data pakan.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateFeed($id, array $data): array
    {
        return $this->request('put', "/feeds/{$id}", $data);
    }

    /**
     * Menonaktifkan pakan (soft delete).
     *
     * @param int $id
     * @return array
     */
    public function deleteFeed($id): array
    {
        return $this->request('delete', "/feeds/{$id}");
    }

    /**
     * Mencatat pemakaian pakan harian.
     *
     * @param array $data
     * @return array
     */
    public function storeFeedingRecord(array $data): array
    {
        return $this->request('post', '/feeds/feeding-record', $data);
    }

    /**
     * Mencatat pengadaan pakan.
     *
     * @param array $data
     * @return array
     */
    public function storeFeedPurchase(array $data): array
    {
        return $this->request('post', '/feeds/purchase-record', $data);
    }

    /**
     * Mengimpor data pakan dari file Excel.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public function importFeeds(UploadedFile $file): array
    {
        return $this->uploadFileRequest('post', '/feeds/import', ['file' => $file], 'file');
    }

    /**
     * Mendapatkan data analitik pakan.
     *
     * @return array
     */
    public function getFeedAnalytics(): array
    {
        return $this->request('get', '/feeds/analytics');
    }

    /**
     * Mendapatkan data penggunaan pakan.
     *
     * @param array $params
     * @return array
     */
    public function getUsageData(array $params = []): array
    {
        return $this->request('get', '/feeds/usage-data', $params);
    }

    /**
     * Mendapatkan data pengadaan pakan.
     *
     * @param array $params
     * @return array
     */
    public function getProcurementData(array $params = []): array
    {
        return $this->request('get', '/feeds/procurement-data', $params);
    }

    // ==================== PREDICTIONS ====================

    /**
     * Mendapatkan daftar prediksi.
     *
     * @param array $params
     * @return array
     */
    public function getPredictions(array $params = []): array
    {
        return $this->request('get', '/predictions', $params);
    }

    /**
     * Mendapatkan riwayat prediksi.
     *
     * @param array $params
     * @return array
     */
    public function getPredictionHistory(array $params = []): array
    {
        return $this->request('get', '/predictions/history', $params);
    }

    /**
     * Mendapatkan data korelasi.
     *
     * @return array
     */
    public function getCorrelationData(): array
    {
        return $this->request('get', '/predictions/correlation');
    }

    /**
     * Membuat prediksi baru.
     *
     * @param array $data
     * @return array
     */
    public function createPrediction(array $data): array
    {
        return $this->request('post', '/predictions', $data);
    }

    // ==================== DASHBOARD ====================

    /**
     * Mendapatkan data overview dashboard.
     *
     * @return array
     */
    public function getDashboardOverview(): array
    {
        return $this->request('get', '/dashboard/overview');
    }

    /**
     * Mendapatkan data analitik kandang untuk dashboard.
     *
     * @return array
     */
    public function getDashboardPenAnalytics(): array
    {
        return $this->request('get', '/dashboard/pen-analytics');
    }

    /**
     * Mendapatkan data statistik dashboard.
     *
     * @return array
     */
    public function getDashboardStatistics(): array
    {
        return $this->request('get', '/dashboard/statistics');
    }

    // ==================== REPORTS ====================

    /**
     * Mendapatkan ringkasan laporan.
     *
     * @return array
     */
    public function getReportSummary(): array
    {
        return $this->request('get', '/reports/summary');
    }

    /**
     * Mendapatkan data performa laporan.
     *
     * @return array
     */
    public function getReportPerformance(): array
    {
        return $this->request('get', '/reports/performance');
    }

    /**
     * Mendapatkan data pertumbuhan laporan.
     *
     * @return array
     */
    public function getReportGrowth(): array
    {
        return $this->request('get', '/reports/growth');
    }

    /**
     * Mendapatkan data keuangan laporan.
     *
     * @return array
     */
    public function getReportFinancial(): array
    {
        return $this->request('get', '/reports/financial');
    }

    // ==================== PROGRAM ====================

    /**
     * Mendapatkan data fattening.
     *
     * @return array
     */
    public function getFatteningData(): array
    {
        return $this->request('get', '/program/fattening');
    }

    /**
     * Mendapatkan data fattening lengkap.
     *
     * @return array
     */
    public function getFatteningDetailed(): array
    {
        return $this->request('get', '/program/fattening-detailed');
    }

    /**
     * Mendapatkan data timbang fattening dengan filter.
     *
     * @param array $params
     * @return array
     */
    public function getFatteningTimbangData(array $params = []): array
    {
        return $this->request('get', '/program/fattening-timbang', $params);
    }

    /**
     * Mendapatkan data ADG & FCR fattening dengan filter.
     *
     * @param array $params
     * @return array
     */
    public function getFatteningAdgFcrData(array $params = []): array
    {
        return $this->request('get', '/program/fattening-adg-fcr', $params);
    }

    /**
     * Mendapatkan data breeding.
     *
     * @return array
     */
    public function getBreedingData(): array
    {
        return $this->request('get', '/program/breeding');
    }

    /**
     * Mendapatkan data keluarga ternak.
     *
     * @param string $earTag
     * @return array
     */
    public function getFamily(string $earTag): array
    {
        return $this->request('get', '/program/family', ['ear_tag' => $earTag]);
    }

    /**
     * Mendapatkan data induk betina (breeding).
     *
     * @param array $params
     * @return array
     */
    public function getBreedingIndukData(array $params = []): array
    {
        return $this->request('get', '/program/breeding/indukan', $params);
    }

    /**
     * Mendapatkan data pejantan (breeding).
     *
     * @param array $params
     * @return array
     */
    public function getBreedingJantanData(array $params = []): array
    {
        return $this->request('get', '/program/breeding/pejantan', $params);
    }

    /**
     * Mendapatkan data anakan (breeding).
     *
     * @param array $params
     * @return array
     */
    public function getBreedingAnakanData(array $params = []): array
    {
        return $this->request('get', '/program/breeding/anakan', $params);
    }

    /**
     * Mendapatkan data kawin & IB (breeding).
     *
     * @param array $params
     * @return array
     */
    public function getBreedingKawinIbData(array $params = []): array
    {
        return $this->request('get', '/program/breeding/kawin-ib', $params);
    }

    // ==================== LOGBOOK ====================

    /**
     * Mendapatkan data logbook.
     *
     * @param array $params
     * @return array
     */
    public function getLogbookData(array $params = []): array
    {
        return $this->request('get', '/logbook', $params);
    }

    // ==================== HPP ====================

    /**
     * Mendapatkan data HPP.
     *
     * @return array
     */
    public function getHppData(): array
    {
        return $this->request('get', '/hpp');
    }

    /**
     * Memperbarui data HPP.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateHpp($id, array $data): array
    {
        return $this->request('put', "/hpp/{$id}", $data);
    }

    // ==================== NOTIFIKASI / NOTIFICATIONS ====================

    /**
     * Mendapatkan data notifikasi (endpoint lama).
     *
     * @return array
     */
    public function getNotifikasiData(): array
    {
        return $this->request('get', '/notifikasi');
    }

    /**
     * Mendapatkan daftar notifikasi.
     *
     * @param array $params
     * @return array
     */
    public function getNotifications(array $params = []): array
    {
        return $this->request('get', '/notifications', $params);
    }

    /**
     * Mendapatkan jumlah notifikasi belum dibaca.
     *
     * @return array
     */
    public function getUnreadCount(): array
    {
        return $this->request('get', '/notifications/unread-count');
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca.
     *
     * @param string $notificationId
     * @return array
     */
    public function markNotificationAsRead(string $notificationId): array
    {
        return $this->request('post', "/notifications/{$notificationId}/mark-as-read");
    }

    /**
     * Menandai semua notifikasi sebagai sudah dibaca.
     *
     * @return array
     */
    public function markAllNotificationsAsRead(): array
    {
        return $this->request('post', '/notifications/mark-all-as-read');
    }
}