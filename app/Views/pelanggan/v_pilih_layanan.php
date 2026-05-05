<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<p class="text-muted mb-4">Pilih jenis servis yang Anda butuhkan untuk motor Anda.</p>

<div class="row g-3">
  <?php foreach ($services as $s): ?>
  <div class="col-md-6 col-lg-4">
    <div class="card h-100" style="cursor:pointer;transition:transform .15s,box-shadow .15s"
         onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''"
         onclick="window.location='/pelanggan/booking/jadwal/<?= $s['id'] ?>'">
      <div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="rounded-circle d-flex align-items-center justify-content-center"
               style="width:52px;height:52px;background:#e7f1ff;flex-shrink:0">
            <i class="bi bi-tools text-primary fs-5"></i>
          </div>
          <div>
            <h6 class="fw-bold mb-0"><?= esc($s['name']) ?></h6>
            <small class="text-muted"><?= $s['duration_min'] ?> menit</small>
          </div>
        </div>

        <p class="text-muted small mb-3" style="line-height:1.5">
          <?= esc(substr($s['description'] ?? '', 0, 90)) ?>...
        </p>

        <div class="d-flex align-items-center gap-1 mb-2">
          <?php $avg = round($s['avg_rating']); for ($i = 1; $i <= 5; $i++): ?>
            <i class="bi bi-star<?= $i <= $avg ? '-fill' : '' ?>" style="color:#f4a100;font-size:.75rem"></i>
          <?php endfor; ?>
          <span class="text-muted small ms-1">(<?= $s['total_reviews'] ?>)</span>
        </div>

        <div class="d-flex justify-content-between align-items-center">
          <span class="fw-bold text-primary fs-6">Rp <?= number_format($s['price'], 0, ',', '.') ?></span>
          <span class="btn btn-primary btn-sm">
            <i class="bi bi-calendar-plus me-1"></i>Booking
          </span>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

  <?php if (empty($services)): ?>
    <div class="col-12 text-center text-muted py-5">
      <i class="bi bi-tools fs-1 d-block mb-3 opacity-25"></i>
      Belum ada layanan tersedia.
    </div>
  <?php endif; ?>
</div>

<?= $this->endSection() ?>
