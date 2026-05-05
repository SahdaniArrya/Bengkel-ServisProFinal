<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<!-- Filter -->
<form method="get" class="row g-2 mb-3">
  <div class="col-md-3">
    <select name="status" class="form-select form-select-sm">
      <option value="">Semua Status</option>
      <?php foreach (['pending','confirmed','in_progress','done','cancelled'] as $s): ?>
        <option value="<?= $s ?>" <?= ($status ?? '') == $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <input type="date" name="date" class="form-control form-control-sm" value="<?= $date ?? '' ?>">
  </div>
  <div class="col-auto">
    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>
    <a href="/admin/bookings" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
  </div>
</form>

<table class="table table-borderless datatable">
  <thead>
    <tr>
      <th>#</th>
      <th>Pelanggan</th>
      <th>Layanan</th>
      <th>Tanggal & Slot</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($bookings as $i => $b): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td>
        <strong><?= esc($b['user_name']) ?></strong><br>
        <small class="text-muted"><?= esc($b['user_phone']) ?></small>
      </td>
      <td>
        <?= esc($b['service_name']) ?><br>
        <small class="text-muted">Rp <?= number_format($b['price'], 0, ',', '.') ?></small>
      </td>
      <td>
        <?= date('d M Y', strtotime($b['available_date'])) ?><br>
        <small class="text-muted"><?= substr($b['slot_time'], 0, 5) ?> WIB</small>
      </td>
      <td>
        <?php
        $badges = ['pending'=>'warning','confirmed'=>'info','in_progress'=>'primary','done'=>'success','cancelled'=>'danger'];
        $labels = ['pending'=>'Pending','confirmed'=>'Dikonfirmasi','in_progress'=>'Proses','done'=>'Selesai','cancelled'=>'Batal'];
        ?>
        <span class="badge bg-<?= $badges[$b['status']] ?? 'secondary' ?>">
          <?= $labels[$b['status']] ?? $b['status'] ?>
        </span>
      </td>
      <td>
        <a href="/admin/bookings/<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary">
          <i class="bi bi-eye"></i> Detail
        </a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($bookings)): ?>
      <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada booking.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<?= $pager->links() ?>

<?= $this->endSection() ?>
