<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm mb-4" style="border-top: 3px solid #dc3545;">
      <div class="card-body pt-4">
        <h5 class="card-title pb-2" style="border-bottom: 2px solid #dc3545; display: inline-block; padding-bottom: 5px; margin-bottom: 25px; color: #000; font-weight: 700;">
          Invoice Pembayaran (UAS)
        </h5>

        <div class="row mb-3">
          <div class="col-6 text-muted">ID Booking</div>
          <div class="col-6 text-end fw-bold text-dark">#<?= esc($booking['id']) ?></div>
        </div>

        <div class="row mb-3">
          <div class="col-6 text-muted">Layanan Servis</div>
          <div class="col-6 text-end fw-bold text-danger"><?= esc($booking['service_name']) ?></div>
        </div>

        <div class="row mb-3">
          <div class="col-6 text-muted">Teknisi / Mekanik</div>
          <div class="col-6 text-end text-dark"><?= esc($booking['staff_name'] ?? '-') ?></div>
        </div>

        <div class="row mb-3">
          <div class="col-6 text-muted">Tanggal Booking</div>
          <div class="col-6 text-end text-dark"><?= date('d F Y', strtotime($booking['available_date'])) ?></div>
        </div>

        <div class="row mb-3">
          <div class="col-6 text-muted">Waktu / Jam</div>
          <div class="col-6 text-end text-dark"><?= substr($booking['slot_time'], 0, 5) ?> WIB</div>
        </div>

        <hr class="my-4">

        <div class="row mb-4 align-items-center">
          <div class="col-6"><h5 class="fw-bold text-dark mb-0">Total Pembayaran</h5></div>
          <div class="col-6 text-end"><h4 class="fw-bold text-danger mb-0">Rp <?= number_format($booking['price'], 0, ',', '.') ?></h4></div>
        </div>

        <form action="/pelanggan/booking/pay-process/<?= $booking['id'] ?>" method="post">
          <?= csrf_field() ?>
          
          <div class="mb-4">
            <label class="form-label fw-bold text-dark mb-3"><i class="bi bi-wallet2 me-1"></i> Pilih Metode Pembayaran (Simulasi)</label>
            
            <div class="row g-2">
              <div class="col-6">
                <input type="radio" class="btn-check" name="payment_type" id="qris" value="QRIS" checked autocomplete="off">
                <label class="btn btn-outline-danger w-100 py-3 text-center" for="qris">
                  <i class="bi bi-qr-code-scan fs-3 d-block mb-1"></i>
                  QRIS (Gopay/OVO/Dana)
                </label>
              </div>
              <div class="col-6">
                <input type="radio" class="btn-check" name="payment_type" id="bank" value="Bank Transfer" autocomplete="off">
                <label class="btn btn-outline-danger w-100 py-3 text-center" for="bank">
                  <i class="bi bi-bank fs-3 d-block mb-1"></i>
                  Transfer Bank (Virtual Account)
                </label>
              </div>
            </div>
          </div>

          <div class="alert alert-warning border-0 small text-muted">
            <i class="bi bi-info-circle me-1"></i> Ini adalah halaman simulasi untuk keperluan demo project UAS. Menekan tombol di bawah akan langsung menandai transaksi ini sebagai <strong>LUNAS</strong>.
          </div>

          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-danger py-3 fw-bold" style="font-size: 16px;">
              <i class="bi bi-shield-check me-2"></i> Konfirmasi Pembayaran
            </button>
            <a href="/pelanggan/riwayat" class="btn btn-link text-muted mt-2 text-center small">Kembali</a>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
