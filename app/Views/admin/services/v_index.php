<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <div></div>
  <a href="/admin/services/create" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i> Tambah Layanan
  </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<table class="table table-borderless datatable">
  <thead>
    <tr>
      <th>#</th>
      <th>Nama Layanan</th>
      <th>Harga</th>
      <th>Durasi</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($services as $i => $s): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td>
        <strong><?= esc($s['name']) ?></strong><br>
        <small class="text-muted"><?= esc(substr($s['description'] ?? '', 0, 60)) ?>...</small>
      </td>
      <td>Rp <?= number_format($s['price'], 0, ',', '.') ?></td>
      <td><?= $s['duration_min'] ?> menit</td>
      <td>
        <?php if ($s['is_active']): ?>
          <span class="badge bg-success">Aktif</span>
        <?php else: ?>
          <span class="badge bg-secondary">Nonaktif</span>
        <?php endif; ?>
      </td>
      <td>
        <a href="/admin/services/edit/<?= $s['id'] ?>" class="btn btn-sm btn-outline-warning">
          <i class="bi bi-pencil"></i>
        </a>
        <a href="/admin/services/delete/<?= $s['id'] ?>"
           class="btn btn-sm btn-outline-danger"
           onclick="return confirm('Yakin hapus layanan ini?')">
          <i class="bi bi-trash"></i>
        </a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($services)): ?>
      <tr><td colspan="6" class="text-center text-muted py-3">Belum ada layanan.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<?= $this->endSection() ?>
