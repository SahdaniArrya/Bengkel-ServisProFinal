<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('errors')): ?>
  <div class="alert alert-danger">
    <ul class="mb-0 ps-3">
      <?php foreach (session()->getFlashdata('errors') as $e): ?>
        <li><?= esc($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php
$isEdit = $service !== null;
$action = $isEdit ? '/admin/services/update/' . $service['id'] : '/admin/services/store';
?>

<form action="<?= $action ?>" method="post" enctype="multipart/form-data" class="row g-3">
  <?= csrf_field() ?>

  <div class="col-md-8">
    <label class="form-label">Nama Layanan <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control"
           value="<?= old('name', $service['name'] ?? '') ?>"
           placeholder="cth: Servis Rutin (Ganti Oli)" required>
  </div>

  <div class="col-md-4">
    <label class="form-label">Status</label>
    <div class="form-check form-switch mt-2">
      <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
             value="1" <?= old('is_active', $service['is_active'] ?? 1) ? 'checked' : '' ?>>
      <label class="form-check-label" for="isActive">Layanan Aktif</label>
    </div>
  </div>

  <div class="col-12">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" class="form-control" rows="3"
              placeholder="Jelaskan apa saja yang termasuk dalam layanan ini..."><?= old('description', $service['description'] ?? '') ?></textarea>
  </div>

  <div class="col-md-6">
    <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
    <div class="input-group">
      <span class="input-group-text">Rp</span>
      <input type="number" name="price" class="form-control"
             value="<?= old('price', $service['price'] ?? '') ?>"
             min="0" step="1000" required>
    </div>
  </div>

  <div class="col-md-6">
    <label class="form-label">Durasi (menit) <span class="text-danger">*</span></label>
    <div class="input-group">
      <input type="number" name="duration_min" class="form-control"
             value="<?= old('duration_min', $service['duration_min'] ?? 60) ?>"
             min="15" step="15" required>
      <span class="input-group-text">menit</span>
    </div>
  </div>

  <div class="col-12">
    <label class="form-label">Foto Layanan</label>
    <?php if ($isEdit && !empty($service['photo'])): ?>
      <div class="mb-2">
        <img src="<?= base_url('uploads/services/' . $service['photo']) ?>"
             style="max-width:180px;border-radius:8px" alt="Foto saat ini">
        <div class="text-muted small mt-1">Foto saat ini. Upload baru untuk mengganti.</div>
      </div>
    <?php endif; ?>
    <input type="file" name="photo" class="form-control" accept="image/*" id="photoInput">
    <img id="photoPreview" style="max-width:200px;border-radius:8px;margin-top:8px;display:none" alt="Preview">
  </div>

  <div class="col-12">
    <button type="submit" class="btn btn-primary">
      <i class="bi bi-save me-1"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Layanan' ?>
    </button>
    <a href="/admin/services" class="btn btn-outline-secondary ms-2">Batal</a>
  </div>
</form>

<script>
document.getElementById('photoInput').addEventListener('change', function () {
  const file = this.files[0];
  const preview = document.getElementById('photoPreview');
  if (file) {
    const reader = new FileReader();
    reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
    reader.readAsDataURL(file);
  } else {
    preview.style.display = 'none';
  }
});
</script>

<?= $this->endSection() ?>
