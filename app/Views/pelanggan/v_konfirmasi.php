<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Konfirmasi Booking</h5>
        <p class="text-muted small mb-4">Periksa detail booking Anda sebelum melanjutkan.</p>

        <table class="table table-borderless">
          <tr>
            <th style="width:130px">Layanan</th>
            <td><strong><?= esc($service['name']) ?></strong></td>
          </tr>
          <tr>
            <th>Tanggal</th>
            <td><?= date('l, d M Y', strtotime($schedule['available_date'])) ?></td>
          </tr>
          <tr>
            <th>Slot Waktu</th>
            <td><?= substr($schedule['slot_time'], 0, 5) ?> WIB</td>
          </tr>
          <tr>
            <th>Durasi</th>
            <td><?= $service['duration_min'] ?> menit</td>
          </tr>
          <tr>
            <th>Harga</th>
            <td class="fw-bold text-primary fs-6">Rp <?= number_format($service['price'], 0, ',', '.') ?></td>
          </tr>
        </table>

        <form action="/pelanggan/booking/store" method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
          <input type="hidden" name="schedule_id" value="<?= $schedule['id'] ?>">

          <div class="mb-4">
            <label class="form-label">Catatan Tambahan <span class="text-muted small">(opsional)</span></label>
            <textarea name="notes" class="form-control" rows="3"
                      placeholder="cth: Honda Beat 2020, plat H 1234 AB, keluhan motor..."></textarea>
          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-circle me-1"></i> Konfirmasi & Buat Booking
            </button>
            <a href="/pelanggan/booking/jadwal/<?= $service['id'] ?>" class="btn btn-outline-secondary">
              <i class="bi bi-arrow-left me-1"></i> Pilih Slot Lain
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
