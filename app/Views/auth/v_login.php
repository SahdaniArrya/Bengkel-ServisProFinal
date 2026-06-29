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
              <h5 class="card-title text-center pb-0 fs-4">Login ke Akun Anda</h5>
              <p class="text-center small text-muted">Masukkan email & password untuk login</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
              <div class="alert alert-danger py-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                <?= session()->getFlashdata('error') ?>
              </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
              <div class="alert alert-success py-2">
                <i class="bi bi-check-circle me-1"></i>
                <?= session()->getFlashdata('success') ?>
              </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
              <div class="alert alert-danger py-2">
                <ul class="mb-0 ps-3">
                  <?php foreach (session()->getFlashdata('errors') as $e): ?>
                    <li><?= esc($e) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <form action="/auth/login" method="post" class="row g-3">
              <?= csrf_field() ?>

              <div class="col-12">
                <label class="form-label">Email</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                  <input type="email" name="email" class="form-control"
                         value="<?= old('email') ?>" placeholder="email@contoh.com" required>
                </div>
              </div>

              <div class="col-12">
                <label class="form-label">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock"></i></span>
                  <input type="password" name="password" class="form-control"
                         placeholder="Password" required>
                </div>
              </div>

              <div class="col-12">
                <button class="btn btn-primary w-100" type="submit">
                  <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                </button>
              </div>

              <div class="col-12 text-center">
                <p class="small mb-0">
                  Belum punya akun?
                  <a href="/auth/register">Daftar di sini</a>
                </p>
              </div>
            </form>

            <div class="mt-3 p-3 bg-light border-start border-4 border-danger rounded small text-muted text-center">
              Akses sistem informasi manajemen <strong>Bengkel ServisPro</strong>. Silakan masuk menggunakan akun Anda untuk mengelola layanan servis atau melakukan reservasi secara online.
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
