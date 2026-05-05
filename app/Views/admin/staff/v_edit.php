<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Edit Data Staff</h5>

        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach(session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/staff/update/' . $staff['id']) ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="row mb-3">
                <label for="name" class="col-sm-2 col-form-label">Nama Lengkap</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $staff['name']) ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="email" class="col-sm-2 col-form-label">Email Login</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user['email']) ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="password" class="col-sm-2 col-form-label">Password Login</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="password" name="password" minlength="6">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                </div>
            </div>

            <div class="row mb-3">
                <label for="phone" class="col-sm-2 col-form-label">Nomor Telepon</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= old('phone', $staff['phone']) ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="specialization" class="col-sm-2 col-form-label">Spesialisasi</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="specialization" name="specialization" value="<?= old('specialization', $staff['specialization']) ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="is_active" class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= old('is_active', $staff['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="<?= base_url('admin/staff') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>

<?= $this->endSection() ?>
