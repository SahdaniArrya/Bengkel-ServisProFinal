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
    <h5 class="card-title mb-4" style="border-bottom: 2px solid #dc3545; display: inline-block; padding-bottom: 5px; color: #000; font-weight: 700;">Daftar Pengguna</h5>
    <div class="table-responsive">
      <table class="table table-borderless table-striped text-center align-middle" style="font-size: 14px;">
        <thead class="bg-light text-muted" style="font-size: 13px; font-weight: 600;">
          <tr>
            <th class="py-3">NO</th>
            <th class="py-3 text-start">NAMA</th>
            <th class="py-3 text-start">EMAIL</th>
            <th class="py-3">TELEPON</th>
            <th class="py-3">ROLE</th>
            <th class="py-3">STATUS</th>
            <th class="py-3">AKSI</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $i => $user): ?>
          <tr style="border-bottom: 1px solid #f0f0f0;">
            <td class="py-3"><?= $i + 1 ?></td>
            <td class="py-3 text-start text-dark"><?= esc($user['name']) ?></td>
            <td class="py-3 text-start"><?= esc($user['email']) ?></td>
            <td class="py-3"><?= esc($user['phone']) ?></td>
            <td class="py-3">
              <?php if ($user['role'] == 'admin'): ?>
                  <span class="badge bg-danger px-3 py-2 rounded-pill">Admin</span>
              <?php elseif ($user['role'] == 'staff'): ?>
                  <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Staff</span>
              <?php else: ?>
                  <span class="badge bg-info text-dark px-3 py-2 rounded-pill">Pelanggan</span>
              <?php endif; ?>
            </td>
            <td class="py-3">
              <?php if ($user['is_active']): ?>
                <span class="badge bg-success px-3 py-2 rounded-pill">Aktif</span>
              <?php else: ?>
                <span class="badge bg-secondary px-3 py-2 rounded-pill">Nonaktif</span>
              <?php endif; ?>
            </td>
            <td class="py-3">
              <?php if ($user['id'] !== session()->get('user_id')): ?>
                <a href="/admin/users/<?= $user['id'] ?>" class="btn btn-sm btn-warning text-white me-1" style="border-radius: 4px;" title="Detail Pengguna">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="/admin/users/delete/<?= $user['id'] ?>" class="btn btn-sm btn-danger text-white" style="border-radius: 4px;" onclick="return confirm('Yakin ingin menghapus pengguna ini?');" title="Hapus">
                  <i class="bi bi-trash"></i>
                </a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($users)): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pengguna.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
