<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card" style="border-top: 3px solid #dc3545;">
  <div class="card-body pt-4">
    <h5 class="card-title pb-2" style="border-bottom: 2px solid #dc3545; display: inline-block; padding-bottom: 5px; margin-bottom: 25px; color: #000; font-weight: 700;">
      Edit Profile
    </h5>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="/profile/update" method="post">
      <div class="row mb-4 align-items-center">
        <label class="col-sm-3 col-form-label text-dark">Full Name</label>
        <div class="col-sm-9">
          <input type="text" name="name" class="form-control" value="<?= esc($user['name']) ?>" required>
        </div>
      </div>

      <div class="row mb-4 align-items-center">
        <label class="col-sm-3 col-form-label text-dark">Phone</label>
        <div class="col-sm-9">
          <input type="text" name="phone" class="form-control" value="<?= esc($user['phone']) ?>" required>
        </div>
      </div>

      <div class="row mb-4 align-items-center">
        <label class="col-sm-3 col-form-label text-dark">Email</label>
        <div class="col-sm-9">
          <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-9 offset-sm-3 text-center">
          <button type="submit" class="btn btn-danger px-4 py-2" style="font-weight: 600;">
            <i class="bi bi-save me-1"></i> Save Changes
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
