<?php

namespace App\Libraries;

/**
 * WeatherService - Konsumsi API BMKG (Milestone 5)
 * 
 * Library ini mengambil data cuaca dari API publik BMKG Indonesia
 * dan men-cache hasilnya selama 1 jam untuk efisiensi.
 * 
 * API BMKG: https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4={kode_wilayah}
 */
class WeatherService
{
    // Kode wilayah BMKG untuk kota (default: Semarang)
    // Daftar kode: https://ibnux.github.io/BMKG-importer/
    protected string $kotasemarang= '33.74.01.1001'; // Semarang Tengah, Semarang
    protected string $kotaBandung  = '32.73.06.1006'; // Lengkong, Bandung
    protected string $kotaJakarta  = '31.71.03.1001'; // Senen, Jakarta
    protected string $kotaDefault;

    protected int $cacheDuration = 3600; // 1 jam dalam detik
    protected string $cacheKey   = 'bmkg_weather_data';

    public function __construct()
    {
        // Default ke Semarang (lokasi bengkel)
        $this->kotaDefault = $this->kotasemarang;
    }

    /**
     * Ambil data cuaca hari ini (dengan caching)
     * 
     * @param string|null $adm4 Kode wilayah BMKG (opsional)
     * @return array Data cuaca atau array error
     */
    public function getWeather(?string $adm4 = null): array
    {
        $kode     = $adm4 ?? $this->kotaDefault;
        $cacheKey = $this->cacheKey . '_' . str_replace('.', '_', $kode);

        // Cek cache terlebih dahulu
        $cache   = \Config\Services::cache(); //cache 1 jam 
        $cached  = $cache->get($cacheKey);

        if ($cached !== null) {
            $cached['from_cache'] = true;
            return $cached;
        }

        // Ambil dari API BMKG
        $result = $this->fetchFromBMKG($kode);

        // Simpan ke cache jika berhasil
        if ($result['success']) {
            $cache->save($cacheKey, $result, $this->cacheDuration);
        }

        return $result;
    }

    /**
     * Fetch data dari API BMKG menggunakan CURLRequest CI4
     */
    private function fetchFromBMKG(string $kode): array
    {
        try {
            $client = \Config\Services::curlrequest([
                'timeout'         => 10,
                'connect_timeout' => 5,
            ]);

            $url      = 'https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=' . $kode; //tembak
            $response = $client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'User-Agent' => 'BengkelServisPro/1.0',
                ],
                'http_errors' => false,
            ]);

            if ($response->getStatusCode() !== 200) {
                return $this->errorResponse('API BMKG mengembalikan status: ' . $response->getStatusCode());
            }

            $body = json_decode($response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE || empty($body)) {
                return $this->errorResponse('Gagal memparse response dari API BMKG.');
            }

            return $this->parseWeather($body);

        } catch (\Throwable $e) {
            return $this->errorResponse('Koneksi ke API BMKG gagal: ' . $e->getMessage());
        }
    }

    /**
     * Parse dan sederhanakan data BMKG ke format yang mudah dipakai di View
     */
    private function parseWeather(array $body): array
    {
        // Struktur BMKG: data[0].cuaca[jam_sekarang]
        $lokasi  = $body['data'][0]['lokasi']  ?? [];
        $cuacaRaw = $body['data'][0]['cuaca']  ?? [];

        // Cari data cuaca yang paling dekat dengan jam sekarang
        $now    = time();
        $target = null;

        foreach ($cuacaRaw as $periodGroup) {
            foreach ($periodGroup as $period) {
                $periodTime = strtotime($period['local_datetime'] ?? '');
                if ($periodTime >= $now) {
                    $target = $period;
                    break 2;
                }
            }
        }

        // Jika tidak ada periode mendatang, ambil yang pertama
        if (!$target && !empty($cuacaRaw[0][0])) {
            $target = $cuacaRaw[0][0];
        }

        if (!$target) {
            return $this->errorResponse('Data cuaca tidak tersedia untuk wilayah ini.');
        }

        $cuacaKode = $target['weather'] ?? 0;

        return [
            'success'      => true,
            'from_cache'   => false,
            'kota'         => $lokasi['kotkab']    ?? 'Indonesia',
            'provinsi'     => $lokasi['provinsi']  ?? '',
            'suhu'         => $target['t']          ?? '--',
            'suhu_min'     => $target['tmin']       ?? '--',
            'suhu_max'     => $target['tmax']       ?? '--',
            'kelembaban'   => $target['hu']         ?? '--',
            'angin_arah'   => $target['wd']         ?? '--',
            'angin_kecepatan' => $target['ws']      ?? '--',
            'cuaca_kode'   => $cuacaKode,
            'cuaca_desc'   => $this->cuacaDescription($cuacaKode),
            'cuaca_icon'   => $this->cuacaIcon($cuacaKode),
            'cuaca_warna'  => $this->cuacaWarna($cuacaKode),
            'waktu_update' => date('H:i, d M Y'),
            'saran_bengkel'=> $this->saranBengkel($cuacaKode),
        ];
    }

    /**
     * Terjemahan kode cuaca BMKG ke deskripsi bahasa Indonesia
     */
    private function cuacaDescription(int $kode): string
    {
        $map = [
            0   => 'Cerah',
            1   => 'Cerah Berawan',
            2   => 'Cerah Berawan',
            3   => 'Berawan',
            4   => 'Berawan Tebal',
            5   => 'Udara Kabur',
            10  => 'Asap',
            45  => 'Berkabut',
            60  => 'Hujan Ringan',
            61  => 'Hujan Sedang',
            63  => 'Hujan Lebat',
            80  => 'Hujan Lokal',
            95  => 'Hujan Petir',
            97  => 'Hujan Petir Lebat',
        ];
        return $map[$kode] ?? 'Tidak Diketahui';
    }

    /**
     * Emoji icon berdasarkan kode cuaca
     */
    private function cuacaIcon(int $kode): string
    {
        if ($kode === 0)                     return '☀️';
        if (in_array($kode, [1, 2]))         return '⛅';
        if (in_array($kode, [3, 4]))         return '☁️';
        if (in_array($kode, [5, 10, 45]))    return '🌫️';
        if (in_array($kode, [60, 80]))       return '🌧️';
        if (in_array($kode, [61, 63]))       return '⛈️';
        if (in_array($kode, [95, 97]))       return '⛈️';
        return '🌤️';
    }

    /**
     * Warna badge berdasarkan kondisi cuaca
     */
    private function cuacaWarna(int $kode): string
    {
        if ($kode === 0)                     return 'success';
        if (in_array($kode, [1, 2, 3]))      return 'info';
        if (in_array($kode, [4, 5, 10, 45])) return 'warning';
        if (in_array($kode, [60, 80]))       return 'primary';
        if (in_array($kode, [61, 63, 95, 97])) return 'danger';
        return 'secondary';
    }

    /**
     * Saran operasional bengkel berdasarkan cuaca
     */
    private function saranBengkel(int $kode): string
    {
        if ($kode === 0 || in_array($kode, [1, 2])) {
            return '✅ Cuaca cerah! Hari yang baik untuk servis kendaraan di luar ruangan.';
        }
        if (in_array($kode, [3, 4])) {
            return '⚠️ Berawan. Pastikan pencahayaan bengkel mencukupi.';
        }
        if (in_array($kode, [5, 10, 45])) {
            return '⚠️ Jarak pandang terbatas. Pelanggan mungkin terlambat datang.';
        }
        if (in_array($kode, [60, 61, 63, 80])) {
            return '🌧️ Hujan! Siapkan area parkir tertutup. Antisipasi kendaraan yang masuk basah.';
        }
        if (in_array($kode, [95, 97])) {
            return '⛈️ Badai petir! Mohon hindari pekerjaan di area terbuka. Utamakan keselamatan.';
        }
        return '🔧 Persiapkan bengkel seperti biasa.';
    }

    /**
     * Helper untuk membuat response error yang konsisten
     */
    private function errorResponse(string $message): array
    {
        return [
            'success'       => false,
            'from_cache'    => false,
            'error'         => $message,
            'kota'          => 'Tidak tersedia',
            'cuaca_desc'    => 'Data tidak tersedia',
            'cuaca_icon'    => '❓',
            'suhu'          => '--',
            'kelembaban'    => '--',
            'saran_bengkel' => '⚠️ Info cuaca sedang tidak tersedia. Silakan cek secara manual.',
            'waktu_update'  => date('H:i, d M Y'),
        ];
    }
}
