<?= $this->extend('layout_clear') ?>
<?= $this->section('content') ?>

<section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

        <div class="d-flex justify-content-center py-4">
          <a href="/" class="logo d-flex align-items-center w-auto">
            <i class="bi bi-wrench-adjustable-circle-fill text-primary me-2 fs-4"></i>
            <span class="d-none d-lg-block fw-bold">Bengkel ServisPro</span>
          </a>
        </div>

        <div class="card mb-3 w-100">
          <div class="card-body">
            <div class="pt-4 pb-2">
              <h5 class="card-title text-center pb-0 fs-4">Buat Akun Baru</h5>
              <p class="text-center small text-muted">Isi data di bawah untuk mendaftar</p>
            </div>

            <?php if (session()->getFlashdata('errors')): ?>
              <div class="alert alert-danger py-2">
                <ul class="mb-0 ps-3">
                  <?php foreach (session()->getFlashdata('errors') as $e): ?>
                    <li><?= esc($e) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <form action="/auth/register" method="post" class="row g-3">
              <?= csrf_field() ?>

              <div class="col-12">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control"
                       value="<?= old('name') ?>" placeholder="Nama lengkap" required>
              </div>

              <div class="col-12">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= old('email') ?>" placeholder="email@contoh.com" required>
              </div>

              <div class="col-12">
                <label class="form-label">Nomor HP</label>
                <input type="text" name="phone" class="form-control"
                       value="<?= old('phone') ?>" placeholder="08xxxxxxxxxx" required>
              </div>

              <div class="col-12">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control"
                       placeholder="Minimal 6 karakter" required>
              </div>

              <div class="col-12">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="confirm_password" class="form-control"
                       placeholder="Ulangi password" required>
              </div>

              <div class="col-12">
                <button class="btn btn-primary w-100" type="submit">
                  <i class="bi bi-person-check me-1"></i> Daftar Sekarang
                </button>
              </div>

              <div class="col-12 text-center">
                <p class="small mb-0">
                  Sudah punya akun?
                  <a href="/auth/login">Login di sini</a>
                </p>
              </div>
            </form>

          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
