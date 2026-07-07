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

<!-- Widget Cuaca BMKG (Milestone 5) - hanya tampil jika data tersedia -->
<?php if (isset($weather)): ?>
  <?= $this->include('components/weather_widget') ?>
<?php endif; ?>

<div class="card" style="border-top: 3px solid #dc3545;">
  <div class="card-body pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h5 class="card-title mb-0" style="border-bottom: 2px solid #dc3545; display: inline-block; padding-bottom: 5px; color: #000; font-weight: 700;">Riwayat Booking</h5>
      <a href="/pelanggan/booking" class="btn btn-danger btn-sm px-3" style="font-weight: 600;">
        <i class="bi bi-plus-lg me-1"></i> Booking Baru
      </a>
    </div>

    <?php if (empty($bookings)): ?>
      <div class="text-center text-muted py-5">
        <i class="bi bi-calendar-x fs-1 d-block mb-3 opacity-25"></i>
        <p>Belum ada riwayat booking.</p>
        <a href="/pelanggan/booking" class="btn btn-danger">Buat Booking Pertama</a>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-borderless table-striped text-center align-middle" style="font-size: 14px;">
          <thead class="bg-light" style="font-size: 13px; font-weight: 700;">
            <tr>
              <th class="py-3 text-danger">#</th>
              <th class="py-3 text-danger text-start">LAYANAN</th>
              <th class="py-3 text-danger">TANGGAL & SLOT</th>
              <th class="py-3 text-danger">TEKNISI</th>
              <th class="py-3 text-danger">HARGA</th>
              <th class="py-3 text-danger">STATUS</th>
              <th class="py-3 text-danger">AKSI</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bookings as $i => $b): ?>
            <tr style="border-bottom: 1px solid #f0f0f0;">
              <td class="py-3"><?= $i + 1 ?></td>
              <td class="py-3 text-start">
                <strong class="text-dark"><?= esc($b['service_name']) ?></strong>
                <?php if ($b['notes']): ?>
                  <br><small class="text-muted"><?= esc(substr($b['notes'], 0, 40)) ?>...</small>
                <?php endif; ?>
              </td>
              <td class="py-3">
                <span class="fw-semibold text-dark"><?= date('d M Y', strtotime($b['available_date'])) ?></span><br>
                <small class="text-muted"><?= substr($b['slot_time'], 0, 5) ?> WIB</small>
              </td>
              <td class="py-3"><?= esc($b['staff_name'] ?? '-') ?></td>
              <td class="py-3 text-dark fw-semibold">Rp <?= number_format($b['price'], 0, ',', '.') ?></td>
              <td class="py-3" style="width: 180px;">
                <?php
                $badges = [
                    'pending'     => 'bg-warning',
                    'confirmed'   => 'bg-info text-white',
                    'in_progress' => 'bg-primary',
                    'done'        => 'bg-success',
                    'cancelled'   => 'bg-danger'
                ];
                $labels = [
                    'pending'     => 'Booking: Menunggu',
                    'confirmed'   => 'Booking: Dikonfirmasi',
                    'in_progress' => 'Booking: Proses',
                    'done'        => 'Booking: Selesai',
                    'cancelled'   => 'Booking: Batal'
                ];
                
                $statusBg = $badges[$b['status']] ?? 'bg-secondary';
                $statusLabel = $labels[$b['status']] ?? $b['status'];
                ?>
                <!-- Top Badge: Booking Status -->
                <span class="badge <?= $statusBg ?> px-3 py-2 rounded-pill d-block mb-1 shadow-sm" style="font-size: 11px; font-weight: 700; width: 100%;">
                  <?= $statusLabel ?>
                </span>
                
                <!-- Bottom Badge: Payment Status -->
                <?php if ($b['payment_status'] === 'paid'): ?>
                  <span class="badge bg-success px-3 py-2 rounded-pill d-block shadow-sm" style="font-size: 11px; font-weight: 700; width: 100%;">
                    Bayar: Lunas
                  </span>
                <?php else: ?>
                  <span class="badge bg-secondary px-3 py-2 rounded-pill d-block shadow-sm" style="font-size: 11px; font-weight: 700; background-color: #6c757d !important; width: 100%;">
                    Bayar: Belum
                  </span>
                <?php endif; ?>
              </td>
              <td class="py-3" style="width: 120px;">
                <?php if ($b['status'] === 'pending'): ?>
                  <a href="/pelanggan/booking/cancel/<?= $b['id'] ?>"
                     class="btn btn-sm btn-outline-danger w-100"
                     onclick="return confirm('Batalkan booking ini?')">
                    <i class="bi bi-x-circle"></i> Batal
                  </a>
                <?php elseif ($b['status'] === 'confirmed' || $b['status'] === 'in_progress'): ?>
                  <?php if ($b['payment_status'] !== 'paid'): ?>
                    <a href="/pelanggan/booking/payment/<?= $b['id'] ?>" class="btn btn-sm btn-success w-100 py-2 shadow-sm text-white" style="font-weight: 700; font-size: 12px; border-radius: 6px; background-color: #198754 !important;">
                      <i class="bi bi-wallet2"></i> Bayar
                    </a>
                  <?php else: ?>
                    <span class="text-muted small">—</span>
                  <?php endif; ?>
                <?php elseif ($b['status'] === 'done'): ?>
                  <?php if (!$b['review_id']): ?>
                    <a href="/pelanggan/booking/review/<?= $b['id'] ?>" class="btn btn-sm btn-warning w-100 py-2 shadow-sm text-white" style="font-weight: 700; font-size: 12px; border-radius: 6px; background-color: #ffc107 !important;">
                      <i class="bi bi-star"></i> Ulas
                    </a>
                  <?php else: ?>
                    <span class="text-success fw-bold small"><i class="bi bi-check-all"></i> Selesai</span>
                  <?php endif; ?>
                <?php else: ?>
                  <span class="text-muted small">—</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php if (session()->getFlashdata('success_booking')): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        title: 'Booking Berhasil!',
        text: 'Terima kasih, pesanan Anda sedang dibuat dan menunggu konfirmasi dari pihak bengkel.',
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-card-list"></i> Lihat Detail Pesanan',
        cancelButtonText: '<i class="bi bi-house"></i> Kembali ke Beranda',
        reverseButtons: true
      }).then((result) => {
        if (!result.isConfirmed) {
          window.location.href = '/pelanggan/dashboard';
        }
        // If confirmed, do nothing because we are already on the riwayat page
      });
    });
  </script>
<?php endif; ?>

<?= $this->endSection() ?>
