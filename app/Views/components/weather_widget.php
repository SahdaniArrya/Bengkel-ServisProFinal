<?php
/**
 * Partial View: Widget Cuaca BMKG
 * Milestone 5 - Integrasi Webservice Client (Konsumsi API BMKG)
 *
 * Cara pakai: pastikan variabel $weather tersedia di controller sebelum include view ini.
 * Contoh: 'weather' => (new \App\Libraries\WeatherService())->getWeather()
 */

// Tentukan background gradient berdasarkan kondisi cuaca
$bgGradient = 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)'; // default: malam/mendung
$textColor   = '#ffffff';
if (isset($weather['cuaca_kode'])) {
    $kode = $weather['cuaca_kode'];
    if ($kode === 0) {
        $bgGradient = 'linear-gradient(135deg, #f7971e 0%, #ffd200 100%)'; // cerah - kuning emas
    } elseif (in_array($kode, [1, 2])) {
        $bgGradient = 'linear-gradient(135deg, #56CCF2 0%, #2F80ED 100%)'; // cerah berawan - biru langit
    } elseif (in_array($kode, [3, 4])) {
        $bgGradient = 'linear-gradient(135deg, #757F9A 0%, #D7DDE8 100%)'; // berawan - abu
        $textColor   = '#333';
    } elseif (in_array($kode, [5, 10, 45])) {
        $bgGradient = 'linear-gradient(135deg, #bdc3c7 0%, #2c3e50 100%)'; // kabut
    } elseif (in_array($kode, [60, 61, 63, 80])) {
        $bgGradient = 'linear-gradient(135deg, #4b6cb7 0%, #182848 100%)'; // hujan - biru gelap
    } elseif (in_array($kode, [95, 97])) {
        $bgGradient = 'linear-gradient(135deg, #1F1C2C 0%, #928DAB 100%)'; // petir - gelap ungu
    }
}
?>

<!-- ============================================================ -->
<!-- Widget Cuaca BMKG - Milestone 5: Integrasi Webservice Client -->
<!-- ============================================================ -->
<div class="row mb-4">
  <div class="col-12">
    <div class="weather-widget rounded-4 overflow-hidden position-relative"
         style="background: <?= $bgGradient ?>; color: <?= $textColor ?>; min-height: 130px;">

      <!-- Decorative circles background -->
      <div class="position-absolute" style="top:-30px;right:-30px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,0.07);pointer-events:none;"></div>
      <div class="position-absolute" style="bottom:-50px;right:60px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,0.05);pointer-events:none;"></div>

      <div class="p-4">
        <!-- Header row -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-broadcast-pin" style="font-size:1.1rem; opacity:0.85;"></i>
            <span class="fw-semibold" style="font-size:0.9rem; opacity:0.85; letter-spacing:0.5px;">INFO CUACA REAL-TIME — BMKG</span>
          </div>
          <span style="font-size:0.72rem; opacity:0.7;">
            <?php if (isset($weather['success']) && $weather['success']): ?>
              <?= $weather['from_cache'] ? '🗄️ Cache' : '🌐 Live API' ?>
              &nbsp;·&nbsp; <?= $weather['waktu_update'] ?> WIB
            <?php else: ?>
              API tidak tersedia
            <?php endif; ?>
          </span>
        </div>

        <?php if (isset($weather['success']) && $weather['success']): ?>
        <!-- Main weather content -->
        <div class="row align-items-center g-3">

          <!-- Kolom 1: Icon + Kondisi -->
          <div class="col-auto text-center">
            <div style="font-size:3.8rem; line-height:1; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.25));">
              <?= $weather['cuaca_icon'] ?>
            </div>
          </div>

          <!-- Kolom 2: Suhu utama -->
          <div class="col-auto" style="border-right:1px solid rgba(255,255,255,0.3); padding-right:1.5rem;">
            <div style="font-size:3rem; font-weight:800; line-height:1; letter-spacing:-2px;">
              <?= $weather['suhu'] ?><span style="font-size:1.5rem; font-weight:400;">°C</span>
            </div>
            <div style="font-size:0.82rem; opacity:0.8; margin-top:4px;">
              <span class="me-2">↓ <?= $weather['suhu_min'] ?>°</span>
              <span>↑ <?= $weather['suhu_max'] ?>°</span>
            </div>
            <div class="mt-2">
              <span class="badge px-2 py-1 rounded-pill"
                style="background:rgba(255,255,255,0.25); font-size:0.78rem; backdrop-filter:blur(4px);">
                <?= $weather['cuaca_desc'] ?>
              </span>
            </div>
          </div>

          <!-- Kolom 3: Detail cuaca -->
          <div class="col" style="padding-left:1.5rem;">
            <div class="d-flex flex-wrap gap-3">
              <div class="d-flex align-items-center gap-2">
                <div style="background:rgba(255,255,255,0.2); border-radius:8px; padding:6px 10px; backdrop-filter:blur(4px);">
                  <i class="bi bi-droplet-fill" style="color:#7fd8f5;"></i>
                </div>
                <div>
                  <div style="font-size:0.72rem; opacity:0.75;">Kelembaban</div>
                  <div style="font-weight:700;"><?= $weather['kelembaban'] ?>%</div>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <div style="background:rgba(255,255,255,0.2); border-radius:8px; padding:6px 10px; backdrop-filter:blur(4px);">
                  <i class="bi bi-wind" style="color:#b8f5d0;"></i>
                </div>
                <div>
                  <div style="font-size:0.72rem; opacity:0.75;">Angin</div>
                  <div style="font-weight:700;"><?= $weather['angin_kecepatan'] ?> km/h <?= $weather['angin_arah'] ?></div>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <div style="background:rgba(255,255,255,0.2); border-radius:8px; padding:6px 10px; backdrop-filter:blur(4px);">
                  <i class="bi bi-geo-alt-fill" style="color:#ffcf77;"></i>
                </div>
                <div>
                  <div style="font-size:0.72rem; opacity:0.75;">Lokasi</div>
                  <div style="font-weight:700;"><?= $weather['kota'] ?></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Kolom 4: Saran bengkel -->
          <div class="col-12 col-lg-auto mt-2 mt-lg-0">
            <div style="background:rgba(0,0,0,0.2); border-radius:12px; padding:12px 16px; max-width:320px; backdrop-filter:blur(4px);">
              <div style="font-size:0.72rem; opacity:0.8; margin-bottom:4px; text-transform:uppercase; letter-spacing:0.5px;">
                💡 Saran Operasional
              </div>
              <div style="font-size:0.83rem; line-height:1.4;">
                <?= $weather['saran_bengkel'] ?>
              </div>
            </div>
          </div>

        </div>

        <?php else: ?>
        <!-- Error state -->
        <div class="d-flex align-items-center gap-3">
          <div style="font-size:2.5rem;">⚠️</div>
          <div>
            <div class="fw-bold">Info cuaca tidak tersedia</div>
            <div style="font-size:0.82rem; opacity:0.8;"><?= $weather['saran_bengkel'] ?? 'Koneksi ke BMKG gagal.' ?></div>
          </div>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
