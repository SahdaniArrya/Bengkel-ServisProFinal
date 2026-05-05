<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<!-- Kartu Statistik -->
<div class="row">
  <div class="col-xxl-3 col-md-6">
    <div class="card info-card sales-card">
      <div class="card-body">
        <h5 class="card-title">Booking Pending</h5>
        <div class="d-flex align-items-center">
          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background:#fff3cd">
            <i class="bi bi-hourglass-split" style="color:#856404"></i>
          </div>
          <div class="ps-3">
            <h6><?= $stats['pending'] ?></h6>
            <span class="text-muted small">menunggu konfirmasi</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xxl-3 col-md-6">
    <div class="card info-card revenue-card">
      <div class="card-body">
        <h5 class="card-title">Dikonfirmasi</h5>
        <div class="d-flex align-items-center">
          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background:#cff4fc">
            <i class="bi bi-calendar-check" style="color:#055160"></i>
          </div>
          <div class="ps-3">
            <h6><?= $stats['confirmed'] ?></h6>
            <span class="text-muted small">booking aktif</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xxl-3 col-md-6">
    <div class="card info-card">
      <div class="card-body">
        <h5 class="card-title">Selesai</h5>
        <div class="d-flex align-items-center">
          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background:#d1e7dd">
            <i class="bi bi-check-circle" style="color:#0f5132"></i>
          </div>
          <div class="ps-3">
            <h6><?= $stats['done'] ?></h6>
            <span class="text-muted small">servis selesai</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xxl-3 col-md-6">
    <div class="card info-card">
      <div class="card-body">
        <h5 class="card-title">Pendapatan</h5>
        <div class="d-flex align-items-center">
          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background:#d1e7dd">
            <i class="bi bi-currency-dollar" style="color:#0f5132"></i>
          </div>
          <div class="ps-3">
            <h6 style="font-size:1rem">Rp <?= number_format($pendapatan, 0, ',', '.') ?></h6>
            <span class="text-muted small">total terbayar</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Tabel Booking Terbaru -->
<div class="row">
  <div class="col-12">
    <div class="card recent-sales overflow-auto">
      <div class="card-body">
        <h5 class="card-title">Booking Terbaru
          <a href="/admin/bookings" class="btn btn-sm btn-outline-primary float-end">Lihat Semua</a>
        </h5>

        <table class="table table-borderless datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>Pelanggan</th>
              <th>Layanan</th>
              <th>Tanggal</th>
              <th>Slot</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (array_slice($recent, 0, 10) as $i => $b): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td>
                <strong><?= esc($b['user_name']) ?></strong><br>
                <small class="text-muted"><?= esc($b['user_phone']) ?></small>
              </td>
              <td><?= esc($b['service_name']) ?></td>
              <td><?= date('d M Y', strtotime($b['available_date'])) ?></td>
              <td><?= substr($b['slot_time'], 0, 5) ?> WIB</td>
              <td>
                <?php
                $badges = [
                  'pending'     => 'warning',
                  'confirmed'   => 'info',
                  'in_progress' => 'primary',
                  'done'        => 'success',
                  'cancelled'   => 'danger',
                ];
                $badge = $badges[$b['status']] ?? 'secondary';
                $labels = [
                  'pending' => 'Pending', 'confirmed' => 'Dikonfirmasi',
                  'in_progress' => 'Proses', 'done' => 'Selesai', 'cancelled' => 'Batal'
                ];
                ?>
                <span class="badge bg-<?= $badge ?>">
                  <?= $labels[$b['status']] ?? $b['status'] ?>
                </span>
              </td>
              <td>
                <a href="/admin/bookings/<?= $b['id'] ?>" class="btn btn-sm btn-outline-secondary">
                  <i class="bi bi-eye"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
