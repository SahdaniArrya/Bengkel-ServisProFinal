<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card" style="border-top: 3px solid #dc3545;">
  <div class="card-body pt-4">
    <h5 class="card-title pb-2" style="border-bottom: 2px solid #dc3545; display: inline-block; padding-bottom: 5px; margin-bottom: 25px; color: #000; font-weight: 700;">
      Edit Data Staff
    </h5>

    <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Validasi gagal:</strong>
        <ul class="mb-0 mt-2 ps-3">
          <?php foreach (session()->getFlashdata('errors') as $err): ?>
            <li><?= esc($err) ?></li>
          <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <form action="/admin/staff/update/<?= $staff['id'] ?>" method="post">
      <?= csrf_field() ?>

      <div class="row mb-4 align-items-center">
        <label class="col-sm-3 col-form-label text-dark fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
        <div class="col-sm-9">
          <input type="text" name="name" class="form-control"
                 value="<?= old('name', esc($staff['name'])) ?>" required>
        </div>
      </div>

      <div class="row mb-4 align-items-center">
        <label class="col-sm-3 col-form-label text-dark fw-semibold">Nomor Telepon <span class="text-danger">*</span></label>
        <div class="col-sm-9">
          <input type="text" name="phone" class="form-control"
                 value="<?= old('phone', esc($staff['phone'])) ?>" required>
        </div>
      </div>

      <div class="row mb-4 align-items-center">
        <label class="col-sm-3 col-form-label text-dark fw-semibold">Spesialisasi <span class="text-danger">*</span></label>
        <div class="col-sm-9">
          <input type="text" name="specialization" class="form-control"
                 value="<?= old('specialization', esc($staff['specialization'])) ?>"
                 placeholder="cth: Ahli Mesin, Ganti Oli, Kelistrikan..." required>
          <div class="form-text">Keahlian utama staff ini di bengkel.</div>
        </div>
      </div>

      <div class="alert alert-info border-0 small" style="background-color: #e7f3ff;">
        <i class="bi bi-info-circle me-1"></i>
        <strong>Info:</strong> Password dan email login tidak dapat diubah di sini untuk menjaga keamanan akun staff.
        Hubungi admin sistem jika diperlukan perubahan.
      </div>

      <div class="row mt-4">
        <div class="col-sm-9 offset-sm-3 d-flex gap-2">
          <a href="/admin/staff" class="btn btn-light border px-4 py-2" style="font-weight: 500; color: #6c757d;">
            <i class="bi bi-arrow-left me-1"></i> Batal
          </a>
          <button type="submit" class="btn btn-danger px-4 py-2" style="font-weight: 600;">
            <i class="bi bi-save me-1"></i> Simpan Perubahan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
