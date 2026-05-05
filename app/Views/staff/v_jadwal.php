<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<h6 class="text-muted mb-3">Seluruh Jadwal Saya</h6>

<?php if (empty($bookings)): ?>
  <div class="text-center text-muted py-5">
    <i class="bi bi-calendar-x fs-1 d-block mb-3 opacity-25"></i>
    Tidak ada jadwal mendatang.
  </div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>Tanggal & Waktu</th>
          <th>Pelanggan</th>
          <th>Layanan</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookings as $b): ?>
        <tr>
          <td>
            <strong><?= date('d M Y', strtotime($b['available_date'])) ?></strong><br>
            <small class="text-muted"><?= substr($b['slot_time'], 0, 5) ?> WIB</small>
          </td>
          <td>
            <?= esc($b['user_name']) ?>
          </td>
          <td>
            <?= esc($b['service_name']) ?>
          </td>
          <td>
            <?php
            $badges = ['confirmed'=>'info','in_progress'=>'primary','done'=>'success'];
            $labels = ['confirmed'=>'Belum Mulai','in_progress'=>'Sedang Dikerjakan','done'=>'Selesai'];
            ?>
            <span class="badge bg-<?= $badges[$b['status']] ?? 'secondary' ?>">
              <?= $labels[$b['status']] ?? $b['status'] ?>
            </span>
          </td>
          <td>
            <?php if ($b['status'] !== 'done'): ?>
            <form action="/staff/update-status/<?= $b['id'] ?>" method="post" class="d-inline">
              <?= csrf_field() ?>
              <?php if ($b['status'] === 'confirmed'): ?>
                <input type="hidden" name="status" value="in_progress">
                <button class="btn btn-sm btn-primary" title="Mulai Pengerjaan">
                  <i class="bi bi-play-circle"></i> Mulai
                </button>
              <?php elseif ($b['status'] === 'in_progress'): ?>
                <input type="hidden" name="status" value="done">
                <button class="btn btn-sm btn-success" title="Tandai Selesai">
                  <i class="bi bi-check-circle"></i> Selesai
                </button>
              <?php endif; ?>
            </form>
            <?php else: ?>
              <span class="text-muted small"><i class="bi bi-check2-all"></i> Selesai</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>
