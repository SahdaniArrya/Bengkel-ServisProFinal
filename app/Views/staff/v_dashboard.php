<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<h6 class="text-muted mb-3">Jadwal Hari Ini — <?= date('l, d M Y') ?></h6>

<?php if (empty($bookings)): ?>
  <div class="text-center text-muted py-5">
    <i class="bi bi-calendar-check fs-1 d-block mb-3 opacity-25"></i>
    Tidak ada tugas hari ini.
  </div>
<?php else: ?>
  <div class="row g-3">
    <?php foreach ($bookings as $b): ?>
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h6 class="fw-bold mb-1"><?= esc($b['service_name']) ?></h6>
              <p class="text-muted small mb-1">
                <i class="bi bi-person me-1"></i><?= esc($b['user_name']) ?> — <?= esc($b['user_phone']) ?>
              </p>
              <p class="text-muted small mb-2">
                <i class="bi bi-clock me-1"></i><?= substr($b['slot_time'], 0, 5) ?> WIB
              </p>
            </div>
            <?php
            $badges = ['confirmed'=>'info','in_progress'=>'primary','done'=>'success'];
            $labels = ['confirmed'=>'Belum Mulai','in_progress'=>'Sedang Dikerjakan','done'=>'Selesai'];
            ?>
            <span class="badge bg-<?= $badges[$b['status']] ?? 'secondary' ?>">
              <?= $labels[$b['status']] ?? $b['status'] ?>
            </span>
          </div>

          <?php if ($b['status'] !== 'done'): ?>
          <form action="/staff/update-status/<?= $b['id'] ?>" method="post" class="mt-2">
            <?= csrf_field() ?>
            <?php if ($b['status'] === 'confirmed'): ?>
              <input type="hidden" name="status" value="in_progress">
              <button class="btn btn-sm btn-primary w-100">
                <i class="bi bi-play-circle me-1"></i> Mulai Pengerjaan
              </button>
            <?php elseif ($b['status'] === 'in_progress'): ?>
              <input type="hidden" name="status" value="done">
              <button class="btn btn-sm btn-success w-100">
                <i class="bi bi-check-circle me-1"></i> Tandai Selesai
              </button>
            <?php endif; ?>
          </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>
