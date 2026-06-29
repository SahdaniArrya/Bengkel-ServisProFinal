<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card" style="border-top: 3px solid #dc3545;">
  <div class="card-body pt-4">
    <h5 class="card-title pb-2" style="border-bottom: 2px solid #dc3545; display: inline-block; padding-bottom: 5px; margin-bottom: 25px; color: #000; font-weight: 700;">
      Tambah Staff Baru
    </h5>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Validasi gagal, periksa kembali data berikut:</strong>
        <ul class="mb-0 mt-2 ps-3">
          <?php foreach (session()->getFlashdata('errors') as $err): ?>
            <li><?= esc($err) ?></li>
          <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <form action="/admin/staff/store" method="post">
      <div class="row mb-4 align-items-center">
        <label class="col-sm-3 col-form-label text-dark">Nama Lengkap</label>
        <div class="col-sm-9">
          <input type="text" name="name" class="form-control" required>
        </div>
      </div>

      <div class="row mb-4">
        <label class="col-sm-3 col-form-label text-dark">Email Login</label>
        <div class="col-sm-9">
          <input type="email" name="email" class="form-control" required>
          <div class="form-text mt-2">Email ini akan digunakan oleh staff untuk login ke sistem.</div>
        </div>
      </div>

      <div class="row mb-4 align-items-center">
        <label class="col-sm-3 col-form-label text-dark">Password Login</label>
        <div class="col-sm-9">
          <input type="password" name="password" class="form-control" required>
        </div>
      </div>

      <div class="row mb-4 align-items-center">
        <label class="col-sm-3 col-form-label text-dark">Nomor Telepon</label>
        <div class="col-sm-9">
          <input type="text" name="phone" class="form-control" required>
        </div>
      </div>

      <div class="row mb-4 align-items-center">
        <label class="col-sm-3 col-form-label text-dark">Spesialisasi</label>
        <div class="col-sm-9">
          <input type="text" name="specialization" class="form-control" required>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-9 offset-sm-3 text-end">
          <a href="/admin/staff" class="btn btn-light border px-4 py-2 me-2" style="font-weight: 500; color: #6c757d;">Batal</a>
          <button type="submit" class="btn btn-danger px-4 py-2" style="font-weight: 600;">Simpan Staff</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
