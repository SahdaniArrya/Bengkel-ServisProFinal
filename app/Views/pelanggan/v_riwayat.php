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

<div class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-muted small">Total: <?= count($bookings) ?> booking</span>
  <a href="/pelanggan/booking" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i> Booking Baru
  </a>
</div>

<?php if (empty($bookings)): ?>
  <div class="text-center text-muted py-5">
    <i class="bi bi-calendar-x fs-1 d-block mb-3 opacity-25"></i>
    <p>Belum ada riwayat booking.</p>
    <a href="/pelanggan/booking" class="btn btn-primary">Buat Booking Pertama</a>
  </div>
<?php else: ?>
  <table class="table datatable">
    <thead>
      <tr>
        <th>#</th>
        <th>Layanan</th>
        <th>Tanggal & Slot</th>
        <th>Teknisi</th>
        <th>Harga</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($bookings as $i => $b): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td>
          <strong><?= esc($b['service_name']) ?></strong>
          <?php if ($b['notes']): ?>
            <br><small class="text-muted"><?= esc(substr($b['notes'], 0, 40)) ?>...</small>
          <?php endif; ?>
        </td>
        <td>
          <?= date('d M Y', strtotime($b['available_date'])) ?><br>
          <small class="text-muted"><?= substr($b['slot_time'], 0, 5) ?> WIB</small>
        </td>
        <td><?= esc($b['staff_name'] ?? '-') ?></td>
        <td>Rp <?= number_format($b['price'], 0, ',', '.') ?></td>
        <td>
          <?php
          $badges = ['pending'=>'warning','confirmed'=>'info','in_progress'=>'primary','done'=>'success','cancelled'=>'danger'];
          $labels = ['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','in_progress'=>'Proses','done'=>'Selesai','cancelled'=>'Batal'];
          ?>
          <span class="badge bg-<?= $badges[$b['status']] ?? 'secondary' ?> mb-1 d-block">
            Booking: <?= $labels[$b['status']] ?? $b['status'] ?>
          </span>
          <?php if ($b['payment_status']): ?>
              <?php
              $payBadges = ['pending'=>'warning','paid'=>'success','failed'=>'danger','cancelled'=>'danger'];
              $payLabels = ['pending'=>'Belum Lunas','paid'=>'Lunas','failed'=>'Gagal','cancelled'=>'Batal'];
              ?>
              <span class="badge bg-<?= $payBadges[$b['payment_status']] ?? 'secondary' ?> d-block">
                Bayar: <?= $payLabels[$b['payment_status']] ?? $b['payment_status'] ?>
              </span>
          <?php else: ?>
              <span class="badge bg-secondary d-block">Bayar: Belum</span>
          <?php endif; ?>
        </td>
        <td>
          <?php if ($b['status'] === 'pending'): ?>
            <a href="/pelanggan/booking/cancel/<?= $b['id'] ?>"
               class="btn btn-sm btn-outline-danger w-100 mb-1"
               onclick="return confirm('Batalkan booking ini?')">
              <i class="bi bi-x-circle"></i> Batal
            </a>
          <?php endif; ?>

          <?php if ($b['status'] === 'confirmed' && $b['payment_status'] !== 'paid'): ?>
            <a href="/pelanggan/payment/pay/<?= $b['id'] ?>" class="btn btn-sm btn-success w-100 mb-1">
              <i class="bi bi-wallet2"></i> Bayar
            </a>
          <?php endif; ?>

          <?php if ($b['status'] === 'done' && !$b['review_id']): ?>
            <a href="/pelanggan/review/<?= $b['id'] ?>" class="btn btn-sm btn-warning w-100">
              <i class="bi bi-star"></i> Ulas
            </a>
          <?php elseif ($b['status'] === 'done' && $b['review_id']): ?>
            <span class="badge bg-success w-100">Sudah Diulas</span>
          <?php endif; ?>
          
          <?php if ($b['status'] !== 'pending' && $b['status'] !== 'confirmed' && $b['status'] !== 'done'): ?>
              <span class="text-muted small">—</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?= $this->endSection() ?>
