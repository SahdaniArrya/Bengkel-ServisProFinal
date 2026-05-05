<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Tambah Staff Baru</h5>

        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach(session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/staff/store') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="row mb-3">
                <label for="name" class="col-sm-2 col-form-label">Nama Lengkap</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="email" class="col-sm-2 col-form-label">Email Login</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                    <small class="text-muted">Email ini akan digunakan oleh staff untuk login ke sistem.</small>
                </div>
            </div>

            <div class="row mb-3">
                <label for="password" class="col-sm-2 col-form-label">Password Login</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                </div>
            </div>

            <div class="row mb-3">
                <label for="phone" class="col-sm-2 col-form-label">Nomor Telepon</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= old('phone') ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="specialization" class="col-sm-2 col-form-label">Spesialisasi</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="specialization" name="specialization" value="<?= old('specialization') ?>" required>
                </div>
            </div>

            <div class="text-end">
                <a href="<?= base_url('admin/staff') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Staff</button>
            </div>
        </form>

    </div>
</div>

<?= $this->endSection() ?>
