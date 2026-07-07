<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="row">
  <div class="col-lg-7">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Informasi Booking</h5>
        <table class="table table-borderless">
          <tr><th style="width:160px">ID Booking</th><td>#<?= $booking['id'] ?></td></tr>
          <tr><th>Pelanggan</th><td><?= esc($booking['user_name']) ?> &mdash; <?= esc($booking['user_phone']) ?></td></tr>
          <tr><th>Layanan</th><td><?= esc($booking['service_name']) ?></td></tr>
          <tr><th>Harga</th><td>Rp <?= number_format($booking['price'], 0, ',', '.') ?></td></tr>
          <tr><th>Tanggal</th><td><?= date('l, d M Y', strtotime($booking['available_date'])) ?></td></tr>
          <tr><th>Slot Waktu</th><td><?= substr($booking['slot_time'], 0, 5) ?> WIB</td></tr>
          <tr><th>Teknisi</th><td><?= esc($booking['staff_name'] ?? '-') ?></td></tr>
          <tr><th>Catatan</th><td><?= esc($booking['notes'] ?? '-') ?></td></tr>
          <tr>
            <th>Status</th>
            <td>
              <?php
              $badges = ['pending'=>'warning','confirmed'=>'info','in_progress'=>'primary','done'=>'success','cancelled'=>'danger'];
              $labels = ['pending'=>'Pending','confirmed'=>'Dikonfirmasi','in_progress'=>'Sedang Dikerjakan','done'=>'Selesai','cancelled'=>'Dibatalkan'];
              ?>
              <span class="badge bg-<?= $badges[$booking['status']] ?? 'secondary' ?> fs-6">
                <?= $labels[$booking['status']] ?? $booking['status'] ?>
              </span>
            </td>
          </tr>
          <tr><th>Dibuat</th><td><?= date('d M Y H:i', strtotime($booking['created_at'])) ?></td></tr>
        </table>
      </div>
    </div>
  </div>

  <?php if ($booking['status'] === 'pending'): ?>
  <div class="col-lg-5">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Konfirmasi Booking</h5>
        <form action="/admin/bookings/confirm/<?= $booking['id'] ?>" method="post" id="confirmForm">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label">Assign Teknisi <span class="text-danger">*</span></label>
            <select name="staff_id" id="staffSelect" class="form-select" required>
              <option value="">-- Wajib Pilih Teknisi --</option>
              <?php foreach ($staffs as $s): ?>
                <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?> — <?= esc($s['specialization'] ?? '') ?></option>
              <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Silakan pilih teknisi terlebih dahulu.</div>
          </div>
          <button type="submit" class="btn btn-success w-100" id="btnConfirm" disabled>
            <i class="bi bi-check-circle me-1"></i> Konfirmasi Booking
          </button>
        </form>

        <script>
          document.addEventListener('DOMContentLoaded', function() {
            const staffSelect = document.getElementById('staffSelect');
            const btnConfirm = document.getElementById('btnConfirm');
            
            staffSelect.addEventListener('change', function() {
              if(this.value !== "") {
                btnConfirm.disabled = false;
              } else {
                btnConfirm.disabled = true;
              }
            });
          });
        </script>

        <hr>

        <a href="/admin/bookings/reject/<?= $booking['id'] ?>"
           class="btn btn-outline-danger w-100"
           onclick="return confirm('Yakin tolak booking ini?')">
          <i class="bi bi-x-circle me-1"></i> Tolak Booking
        </a>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

<!-- Kartu Status Pembayaran -->
<div class="row mt-3">
  <div class="col-lg-7">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title"><i class="bi bi-credit-card me-1"></i> Status Pembayaran</h5>
        <?php if (!empty($payment)): ?>
          <table class="table table-borderless mb-0">
            <tr><th style="width:160px">Order ID</th><td><code><?= esc($payment['order_id']) ?></code></td></tr>
            <tr>
              <th>Status</th>
              <td>
                <?php
                if ($payment['status'] === 'paid') {
                  echo '<span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>LUNAS</span>';
                } elseif ($payment['status'] === 'pending') {
                  echo '<span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock me-1"></i>Menunggu Pembayaran</span>';
                } elseif ($payment['status'] === 'failed') {
                  echo '<span class="badge bg-danger fs-6"><i class="bi bi-x-circle me-1"></i>Gagal / Kadaluwarsa</span>';
                }
                ?>
              </td>
            </tr>
            <tr><th>Metode Bayar</th><td><?= esc($payment['payment_type'] ?? '-') ?></td></tr>
            <tr><th>Jumlah</th><td><strong>Rp <?= number_format($payment['amount'], 0, ',', '.') ?></strong></td></tr>
            <?php if (!empty($payment['paid_at'])): ?>
            <tr><th>Dibayar Pada</th><td><?= date('d M Y, H:i', strtotime($payment['paid_at'])) ?> WIB</td></tr>
            <?php endif; ?>
          </table>
        <?php else: ?>
          <div class="text-muted py-2">
            <i class="bi bi-info-circle me-1"></i> Pelanggan belum melakukan pembayaran.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<a href="/admin/bookings" class="btn btn-outline-secondary mt-2">
  <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
</a>

<?= $this->endSection() ?>
