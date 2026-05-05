<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<!-- Info layanan -->
<div class="alert alert-primary d-flex align-items-center gap-3 mb-4">
  <i class="bi bi-tools fs-4"></i>
  <div>
    <strong><?= esc($service['name']) ?></strong>
    &nbsp;&mdash;&nbsp; Rp <?= number_format($service['price'], 0, ',', '.') ?>
    &nbsp;&bull;&nbsp; <?= $service['duration_min'] ?> menit
  </div>
</div>

<!-- Filter tanggal -->
<form method="get" class="row g-2 mb-4">
  <div class="col-auto">
    <label class="form-label mb-0 mt-1">Pilih Tanggal:</label>
  </div>
  <div class="col-auto">
    <input type="date" name="date" class="form-control form-control-sm"
           value="<?= $date ?>" min="<?= date('Y-m-d') ?>">
  </div>
  <div class="col-auto">
    <button class="btn btn-sm btn-primary" type="submit">
      <i class="bi bi-search me-1"></i>Cari Slot
    </button>
  </div>
</form>

<?php if (!empty($schedules)): ?>
  <div class="row g-2">
    <?php foreach ($schedules as $sch): ?>
    <?php $full = $sch['booked_count'] >= $sch['capacity']; ?>
    <div class="col-md-3 col-sm-4 col-6">
      <?php if ($full): ?>
        <div class="card text-center p-3 border-secondary opacity-50" style="cursor:not-allowed">
          <div class="fw-bold"><?= substr($sch['slot_time'], 0, 5) ?> WIB</div>
          <small class="text-danger">Penuh</small>
        </div>
      <?php else: ?>
        <a href="/pelanggan/booking/konfirmasi?service_id=<?= $service['id'] ?>&schedule_id=<?= $sch['id'] ?>"
           class="card text-center p-3 border-primary text-decoration-none"
           style="transition:.15s"
           onmouseover="this.style.background='#e7f1ff'"
           onmouseout="this.style.background=''">
          <div class="fw-bold text-primary"><?= substr($sch['slot_time'], 0, 5) ?> WIB</div>
          <small class="text-success">
            Tersisa <?= $sch['capacity'] - $sch['booked_count'] ?> slot
          </small>
        </a>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="text-center text-muted py-5">
    <i class="bi bi-calendar-x fs-1 d-block mb-3 opacity-25"></i>
    Tidak ada slot tersedia untuk tanggal ini.<br>
    Coba pilih tanggal lain.
  </div>
<?php endif; ?>

<div class="mt-4">
  <a href="/pelanggan/booking" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i> Ganti Layanan
  </a>
</div>

<?= $this->endSection() ?>
