<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\BackendApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProgramController extends Controller
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
     * Menampilkan halaman utama Fattening Domba.
     *
     * @return \Illuminate\View\View
     */
    public function fattening()
    {
        return view('program.fattening');
    }

    /**
     * Menampilkan halaman detail per ternak untuk modul Fattening.
     *
     * @return \Illuminate\View\View
     */
    public function fatteningDetail()
    {
        return view('program.fattening-detail');
    }

    /**
     * Menampilkan halaman data timbang untuk modul Fattening.
     *
     * @return \Illuminate\View\View
     */
    public function fatteningTimbang()
    {
        return view('program.fattening-timbang');
    }

    /**
     * Menampilkan halaman analisis ADG & FCR untuk modul Fattening.
     *
     * @return \Illuminate\View\View
     */
    public function fatteningAdgFcr()
    {
        return view('program.fattening-adg-fcr');
    }

    /**
     * Menampilkan halaman utama Breeding Domba.
     *
     * @return \Illuminate\View\View
     */
    public function breeding()
    {
        return view('program.breeding');
    }

    /**
     * Menampilkan halaman data Indukan untuk modul Breeding.
     *
     * @return \Illuminate\View\View
     */
    public function breedingIndukan()
    {
        return view('program.breeding-indukan');
    }

    /**
     * Menampilkan halaman data Pejantan untuk modul Breeding.
     *
     * @return \Illuminate\View\View
     */
    public function breedingPejantan()
    {
        return view('program.breeding-pejantan');
    }

    /**
     * Menampilkan halaman data Anakan untuk modul Breeding.
     *
     * @return \Illuminate\View\View
     */
    public function breedingAnakan()
    {
        return view('program.breeding-anakan');
    }

    /**
     * Menampilkan halaman Data Kawin & IB untuk modul Breeding.
     *
     * @return \Illuminate\View\View
     */
    public function breedingKawinIb()
    {
        return view('program.breeding-kawin-ib');
    }

    /**
     * Mengambil data ringkasan fattening dari backend.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFatteningData()
    {
        try {
            $result = $this->api->getFatteningData();
            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error('Error fetching fattening data: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data fattening'
            ], 500);
        }
    }

    /**
     * Mengambil data fattening lengkap (detail tabel dan statistik) dari backend.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFatteningDetailedData()
    {
        try {
            $result = $this->api->getFatteningDetailed();
            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error('Error fetching fattening detailed data: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data fattening lengkap'
            ], 500);
        }
    }

    /**
     * Mengambil data timbang fattening dengan filter tagging, jenis, dan kategori kandang.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFatteningTimbangData(Request $request)
    {
        try {
            $parameters = [
                'tagging'  => $request->query('tagging', ''),
                'jenis'    => $request->query('jenis', ''),
                'kategori' => $request->query('kategori', '')
            ];
            $result = $this->api->getFatteningTimbangData($parameters);
            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error('Error fetching fattening timbang data: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data timbang fattening'
            ], 500);
        }
    }

    /**
     * Mengambil data ADG & FCR fattening dengan filter tanggal awal dan akhir.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFatteningAdgFcrData(Request $request)
    {
        try {
            $parameters = [
                'start_date' => $request->query('start_date', ''),
                'end_date'   => $request->query('end_date', '')
            ];
            $result = $this->api->getFatteningAdgFcrData($parameters);
            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error('Error fetching fattening ADG & FCR data: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data ADG & FCR fattening'
            ], 500);
        }
    }

    /**
     * Mengambil data breeding dari backend.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBreedingData()
    {
        try {
            $result = $this->api->getBreedingData();

            if (isset($result['redirect']) && $result['redirect'] === true) {
                return response()->json($result, 401);
            }

            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error('Error fetching breeding data: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data breeding: ' . $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil data keluarga ternak berdasarkan ear tag.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFamily(Request $request)
    {
        try {
            $earTag = $request->query('ear_tag');
            $result = $this->api->getFamily($earTag);
            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error('Error fetching family data: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data keluarga'
            ], 500);
        }
    }

    /**
     * Mengambil data induk betina (untuk tabel induk di halaman breeding)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBreedingIndukData(Request $request)
    {
        try {
            $result = $this->api->getBreedingIndukData($request->all());
            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error('Error fetching breeding induk data: ' . $exception->getMessage());
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Mengambil data pejantan (untuk tabel jantan di halaman breeding)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBreedingJantanData(Request $request)
    {
        try {
            $result = $this->api->getBreedingJantanData($request->all());
            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error('Error fetching breeding jantan data: ' . $exception->getMessage());
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Mengambil data anakan (untuk tabel anakan di halaman breeding)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBreedingAnakanData(Request $request)
    {
        try {
            $result = $this->api->getBreedingAnakanData($request->all());
            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error('Error fetching breeding anakan data: ' . $exception->getMessage());
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Mengambil data indukan (alternatif, jika ada panggilan ke /breeding/indukan)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBreedingIndukanData(Request $request)
    {
        return $this->getBreedingIndukData($request);
    }

    /**
     * Mengambil data pejantan (alternatif)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBreedingPejantanData(Request $request)
    {
        return $this->getBreedingJantanData($request);
    }

    /**
     * Mengambil data kawin & IB
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBreedingKawinIbData(Request $request)
    {
        try {
            $result = $this->api->getBreedingKawinIbData($request->all());
            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error('Error fetching breeding kawin-ib data: ' . $exception->getMessage());
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Mengambil detail indukan berdasarkan ear tag.
     * Endpoint ini dipanggil oleh frontend AJAX.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function breedingIndukanDetail(Request $request)
    {
        try {
            $tag = $request->query('tag');
            if (empty($tag)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter tag diperlukan'
                ], 400);
            }

            // Panggil backend melalui service (request() sekarang public)
            $result = $this->api->request('get', '/program/breeding/indukan/detail', ['tag' => $tag]);

            // Validasi response
            if (!is_array($result) || !isset($result['success'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Respons dari server tidak valid'
                ], 500);
            }

            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error('Error fetching breeding indukan detail: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail indukan: ' . $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan data perkawinan baru (Kawin Alami atau IB) melalui proxy ke backend.
     * Metode ini menggunakan autentikasi token (melalui BackendApiService).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeKawinIb(Request $request)
    {
        return response()->json($this->api->request('post', '/program/breeding/kawin-ib/store', $request->all()));
    }

    /**
     * Mendapatkan detail perkawinan (Kawin Alami atau IB) melalui proxy ke backend.
     * Metode ini menggunakan autentikasi token (melalui BackendApiService).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detailKawinIb(Request $request)
    {
        return response()->json($this->api->request('get', '/program/breeding/kawin-ib/detail', $request->query()));
    }

    /**
     * Menyimpan data perkawinan baru (Kawin Alami atau IB) langsung ke backend tanpa token.
     * Metode ini digunakan sebagai alternatif ketika terjadi masalah autentikasi.
     * Endpoint ini memanggil backend secara publik (tanpa token).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeKawinIbPublic(Request $request)
    {
        $backendUrl = rtrim(config('services.backend.base_url'), '/') . '/program/breeding/kawin-ib/store';
        try {
            $response = Http::timeout(30)->post($backendUrl, $request->all());
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke backend: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan detail perkawinan (Kawin Alami atau IB) langsung dari backend tanpa token.
     * Metode ini digunakan sebagai alternatif ketika terjadi masalah autentikasi.
     * Endpoint ini memanggil backend secara publik (tanpa token).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detailKawinIbPublic(Request $request)
    {
        $backendUrl = rtrim(config('services.backend.base_url'), '/') . '/program/breeding/kawin-ib/detail';
        try {
            $response = Http::timeout(30)->get($backendUrl, $request->query());
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke backend: ' . $e->getMessage()
            ], 500);
        }
    }
}