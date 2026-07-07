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

          <div class="row mb-3">
            <div class="col-md-6 mb-3 mb-md-0">
              <label class="form-label">Plat Nomor <span class="text-danger">*</span></label>
              <input type="text" name="plat" class="form-control" placeholder="cth: H 1234 AB" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Jenis/Merek Kendaraan <span class="text-danger">*</span></label>
              <input type="text" name="kendaraan" class="form-control" placeholder="cth: Honda Vario 150" required>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Keluhan Utama <span class="text-danger">*</span></label>
            <input type="text" name="keluhan" class="form-control" placeholder="cth: Ganti oli rutin dan rem bunyi" required>
          </div>

          <div class="mb-4">
            <label class="form-label">Catatan Tambahan <span class="text-muted small">(opsional)</span></label>
            <textarea name="notes" class="form-control" rows="2"
                      placeholder="cth: Tolong dicek juga tekanan bannya"></textarea>
          </div>

          <!-- Tambahkan script SweetAlert2 untuk Notifikasi Popup -->
          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              const form = document.getElementById('bookingForm');
              form.addEventListener('submit', function(e) {
                // Biarkan form tersubmit, tapi tunjukkan popup loading/sukses
                // Form action akan langsung handle redirect, 
                // Namun karena user minta popup setelah klik, kita bisa gunakan return false lalu submit via AJAX,
                // ATAU tangkap session()->getFlashdata('success') di halaman riwayat dan tampilkan popup di sana.
                // Pendekatan terbaik: ubah form submission jadi normal, lalu di halaman riwayat kita tangkap flashdata untuk menampilkan SweetAlert.
              });
            });
          </script>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary" id="btnBuatBooking">
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
