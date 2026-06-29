<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card" style="border-top: 3px solid #dc3545;">
  <div class="card-body pt-4">
    <h5 class="card-title mb-4" style="border-bottom: 2px solid #dc3545; display: inline-block; padding-bottom: 5px; color: #000; font-weight: 700;">
      Detail Pengguna
    </h5>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="row mb-3">
      <div class="col-md-3 text-muted">Nama Lengkap</div>
      <div class="col-md-9 fw-bold"><?= esc($user['name']) ?></div>
    </div>
    
    <div class="row mb-3">
      <div class="col-md-3 text-muted">Email</div>
      <div class="col-md-9"><?= esc($user['email']) ?></div>
    </div>
    
    <div class="row mb-3">
      <div class="col-md-3 text-muted">Telepon</div>
      <div class="col-md-9"><?= esc($user['phone']) ?></div>
    </div>

    <div class="row mb-3">
      <div class="col-md-3 text-muted">Role</div>
      <div class="col-md-9">
        <?php if ($user['role'] == 'admin'): ?>
            <span class="badge bg-danger px-3 py-2 rounded-pill">Admin</span>
        <?php elseif ($user['role'] == 'staff'): ?>
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Staff</span>
        <?php else: ?>
            <span class="badge bg-info text-dark px-3 py-2 rounded-pill">Pelanggan</span>
        <?php endif; ?>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-3 text-muted">Status Akun</div>
      <div class="col-md-9">
        <?php if ($user['is_active']): ?>
          <span class="badge bg-success px-3 py-2 rounded-pill">Aktif</span>
        <?php else: ?>
          <span class="badge bg-secondary px-3 py-2 rounded-pill">Nonaktif</span>
        <?php endif; ?>
      </div>
    </div>

    <hr>

    <div class="d-flex justify-content-between mt-4">
      <a href="/admin/users" class="btn btn-light border px-4">Kembali</a>
      
      <?php if ($user['id'] !== session()->get('user_id')): ?>
        <div>
          <a href="/admin/users/toggle/<?= $user['id'] ?>" class="btn <?= $user['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?> px-4">
            <i class="bi bi-power"></i> <?= $user['is_active'] ? 'Nonaktifkan Pengguna' : 'Aktifkan Pengguna' ?>
          </a>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<?= $this->endSection() ?>
