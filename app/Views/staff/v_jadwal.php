<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="card" style="border-top: 3px solid #dc3545;">
  <div class="card-body pt-4">
    <h5 class="card-title mb-4" style="border-bottom: 2px solid #dc3545; display: inline-block; padding-bottom: 5px; color: #000; font-weight: 700;">Jadwal Kerja Saya</h5>
    
    <?php if (empty($bookings)): ?>
      <div class="text-center text-muted py-5">
        <i class="bi bi-calendar-x fs-1 d-block mb-3 opacity-25"></i>
        Belum ada jadwal kerja mendatang.
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-borderless table-striped text-center align-middle" style="font-size: 14px;">
          <thead class="bg-light text-muted" style="font-size: 13px; font-weight: 600;">
            <tr>
              <th class="py-3">NO</th>
              <th class="py-3 text-start">LAYANAN</th>
              <th class="py-3 text-start">PELANGGAN</th>
              <th class="py-3">TANGGAL & WAKTU</th>
              <th class="py-3">STATUS</th>
              <th class="py-3">AKSI</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bookings as $i => $b): ?>
            <tr style="border-bottom: 1px solid #f0f0f0;">
              <td class="py-3"><?= $i + 1 ?></td>
              <td class="py-3 text-start text-dark fw-semibold"><?= esc($b['service_name']) ?></td>
              <td class="py-3 text-start"><?= esc($b['user_name']) ?></td>
              <td class="py-3">
                <i class="bi bi-calendar me-1 text-danger"></i><?= date('d M Y', strtotime($b['available_date'])) ?><br>
                <small class="text-muted"><i class="bi bi-clock me-1"></i><?= substr($b['slot_time'], 0, 5) ?> WIB</small>
              </td>
              <td class="py-3">
                <?php
                $badges = ['confirmed'=>'info','in_progress'=>'primary','done'=>'success'];
                $labels = ['confirmed'=>'Belum Mulai','in_progress'=>'Sedang Dikerjakan','done'=>'Selesai'];
                ?>
                <span class="badge bg-<?= $badges[$b['status']] ?? 'secondary' ?> px-3 py-2 rounded-pill">
                  <?= $labels[$b['status']] ?? $b['status'] ?>
                </span>
              </td>
              <td class="py-3">
                <?php if ($b['status'] !== 'done'): ?>
                  <form action="/staff/update-status/<?= $b['id'] ?>" method="post">
                    <?= csrf_field() ?>
                    <?php if ($b['status'] === 'confirmed'): ?>
                      <input type="hidden" name="status" value="in_progress">
                      <button class="btn btn-sm btn-outline-primary" style="font-weight: 600;">
                        <i class="bi bi-play-fill"></i> Mulai
                      </button>
                    <?php elseif ($b['status'] === 'in_progress'): ?>
                      <input type="hidden" name="status" value="done">
                      <button class="btn btn-sm btn-outline-success" style="font-weight: 600;">
                        <i class="bi bi-check-lg"></i> Selesai
                      </button>
                    <?php endif; ?>
                  </form>
                <?php else: ?>
                  <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Selesai</span>
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

<?= $this->endSection() ?>
