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
      <h5 class="card-title mb-0" style="border-bottom: 2px solid #dc3545; display: inline-block; padding-bottom: 5px; color: #000; font-weight: 700;">Kelola Layanan Bengkel</h5>
      <a href="/admin/services/create" class="btn btn-danger btn-sm px-3" style="font-weight: 600;">
        <i class="bi bi-plus-circle me-1"></i> Tambah Layanan
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-borderless table-striped text-center align-middle" style="font-size: 14px;">
        <thead class="bg-light text-muted" style="font-size: 13px; font-weight: 600;">
          <tr>
            <th class="py-3">NO</th>
            <th class="py-3 text-start">NAMA LAYANAN</th>
            <th class="py-3">FOTO</th>
            <th class="py-3">HARGA</th>
            <th class="py-3">DURASI</th>
            <th class="py-3">STATUS</th>
            <th class="py-3">AKSI</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($services as $i => $s): ?>
          <tr style="border-bottom: 1px solid #f0f0f0;">
            <td class="py-3"><?= $i + 1 ?></td>
            <td class="py-3 text-start">
              <strong class="text-dark"><?= esc($s['name']) ?></strong>
              <?php if (!empty($s['description'])): ?>
                <br><small class="text-muted"><?= esc(substr($s['description'], 0, 55)) ?>...</small>
              <?php endif; ?>
            </td>
            <td class="py-3">
              <?php if (!empty($s['photo'])): ?>
                <img src="<?= base_url('uploads/services/' . $s['photo']) ?>"
                     alt="Foto"
                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #eee;">
              <?php else: ?>
                <div class="rounded d-inline-flex align-items-center justify-content-center bg-light"
                     style="width: 50px; height: 50px; border: 1px dashed #ccc;">
                  <i class="bi bi-image text-muted" style="font-size: 20px;"></i>
                </div>
              <?php endif; ?>
            </td>
            <td class="py-3 fw-semibold text-dark">Rp <?= number_format($s['price'], 0, ',', '.') ?></td>
            <td class="py-3"><?= $s['duration_min'] ?> menit</td>
            <td class="py-3">
              <?php if ($s['is_active']): ?>
                <span class="badge bg-success px-3 py-2 rounded-pill">Aktif</span>
              <?php else: ?>
                <span class="badge bg-secondary px-3 py-2 rounded-pill">Nonaktif</span>
              <?php endif; ?>
            </td>
            <td class="py-3">
              <a href="/admin/services/edit/<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit Layanan">
                <i class="bi bi-pencil-fill"></i>
              </a>
              <a href="/admin/services/delete/<?= $s['id'] ?>"
                 class="btn btn-sm btn-outline-danger"
                 onclick="return confirm('Yakin hapus layanan ini? Foto yang tersimpan juga akan ikut dihapus.')"
                 title="Hapus Layanan">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($services)): ?>
            <tr><td colspan="7" class="text-center text-muted py-5">
              <i class="bi bi-tools fs-1 d-block mb-3 opacity-25"></i>
              Belum ada layanan yang ditambahkan.
            </td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
