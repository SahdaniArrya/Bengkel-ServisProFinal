<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle-fill me-2"></i>
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="card" style="border-top: 3px solid #dc3545;">
  <div class="card-body pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h5 class="card-title mb-0" style="border-bottom: 2px solid #dc3545; display: inline-block; padding-bottom: 5px; color: #000; font-weight: 700;">Daftar Staff</h5>
      <a href="/admin/staff/create" class="btn btn-danger btn-sm px-3" style="font-weight: 600;">
        <i class="bi bi-plus-circle"></i> Tambah Staff
      </a>
    </div>
    <div class="table-responsive">
      <table class="table table-borderless table-striped text-center align-middle" style="font-size: 14px;">
        <thead class="bg-light text-muted" style="font-size: 13px; font-weight: 600;">
          <tr>
            <th class="py-3">NO</th>
            <th class="py-3 text-start">NAMA</th>
            <th class="py-3 text-start">SPESIALISASI</th>
            <th class="py-3">TELEPON</th>
            <th class="py-3">STATUS</th>
            <th class="py-3">AKSI</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($staffs as $i => $staff): ?>
          <tr style="border-bottom: 1px solid #f0f0f0;">
            <td class="py-3"><?= $i + 1 ?></td>
            <td class="py-3 text-start text-dark fw-semibold"><?= esc($staff['name']) ?></td>
            <td class="py-3 text-start"><?= esc($staff['specialization']) ?></td>
            <td class="py-3"><?= esc($staff['phone']) ?></td>
            <td class="py-3">
              <?php if ($staff['is_active']): ?>
                <span class="badge bg-success px-3 py-2 rounded-pill">Aktif</span>
              <?php else: ?>
                <span class="badge bg-secondary px-3 py-2 rounded-pill">Nonaktif</span>
              <?php endif; ?>
            </td>
            <td class="py-3">
              <!-- Edit -->
              <a href="/admin/staff/edit/<?= $staff['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit Data Staff">
                <i class="bi bi-pencil-fill"></i>
              </a>
              <!-- Toggle Aktif/Nonaktif -->
              <a href="/admin/staff/toggle/<?= $staff['id'] ?>" class="btn btn-sm btn-outline-warning text-warning me-1"
                 title="<?= $staff['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                <i class="bi <?= $staff['is_active'] ? 'bi-power' : 'bi-check-circle' ?>"></i>
              </a>
              <!-- Hapus -->
              <a href="/admin/staff/delete/<?= $staff['id'] ?>" class="btn btn-sm btn-outline-danger"
                 onclick="return confirm('Yakin ingin menghapus staff ini? Data akun login staff juga akan ikut terhapus.')" title="Hapus">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($staffs)): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data staff.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
