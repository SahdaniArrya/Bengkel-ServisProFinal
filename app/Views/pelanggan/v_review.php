<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm mb-4" style="border-top: 3px solid #dc3545;">
      <div class="card-body pt-4">
        <h5 class="card-title pb-2" style="border-bottom: 2px solid #dc3545; display: inline-block; padding-bottom: 5px; margin-bottom: 25px; color: #000; font-weight: 700;">
          Ulasan Layanan Bengkel
        </h5>

        <div class="p-3 bg-light rounded mb-4">
          <h6 class="fw-bold text-dark mb-1"><?= esc($booking['service_name']) ?></h6>
          <p class="text-muted small mb-0">
            Dikerjakan oleh teknisi: <strong><?= esc($booking['staff_name'] ?? '-') ?></strong> pada <?= date('d M Y', strtotime($booking['available_date'])) ?>
          </p>
        </div>

        <form action="/pelanggan/booking/review-store/<?= $booking['id'] ?>" method="post">
          <?= csrf_field() ?>

          <div class="mb-4 text-center">
            <label class="form-label fw-bold text-dark d-block mb-3">Seberapa puas Anda dengan pelayanan kami?</label>
            
            <div class="d-flex justify-content-center gap-3">
              <?php for($i=1; $i<=5; $i++): ?>
                <div>
                  <input type="radio" class="btn-check" name="rating" id="star<?= $i ?>" value="<?= $i ?>" <?= $i === 5 ? 'checked' : '' ?> autocomplete="off">
                  <label class="btn btn-outline-warning rounded-circle d-flex align-items-center justify-content-center" for="star<?= $i ?>" style="width: 50px; height: 50px;">
                    <span class="fs-4 fw-bold"><?= $i ?></span>
                  </label>
                </div>
              <?php endfor; ?>
            </div>
            <div class="form-text mt-2">Pilih nilai kepuasan (1 = Sangat Kecewa, 5 = Sangat Puas)</div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-bold text-dark">Komentar / Saran</label>
            <textarea name="comment" rows="4" class="form-control" placeholder="Tuliskan pengalaman servis Anda di sini..." required></textarea>
          </div>

          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-danger py-3 fw-bold" style="font-size: 16px;">
              <i class="bi bi-send-fill me-2"></i> Kirim Ulasan
            </button>
            <a href="/pelanggan/riwayat" class="btn btn-link text-muted mt-2 text-center small">Kembali</a>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
