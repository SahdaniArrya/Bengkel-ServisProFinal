<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
  <div class="col-xl-4">
    <div class="card">
      <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
        <img src="<?= base_url() ?>NiceAdmin/assetss/img/profile-img.jpg" alt="Profile" class="rounded-circle" style="max-width: 120px;">
        <h2 class="mt-3"><?= esc($user['name']) ?></h2>
        <h3><?= ucfirst(esc($user['role'])) ?></h3>
      </div>
    </div>
  </div>

  <div class="col-xl-8">
    <div class="card">
      <div class="card-body pt-3">
        <h5 class="card-title">Profile Details</h5>
        <div class="row mb-3">
          <div class="col-lg-3 col-md-4 label text-muted">Full Name</div>
          <div class="col-lg-9 col-md-8"><?= esc($user['name']) ?></div>
        </div>
        <div class="row mb-3">
          <div class="col-lg-3 col-md-4 label text-muted">Role</div>
          <div class="col-lg-9 col-md-8"><?= ucfirst(esc($user['role'])) ?></div>
        </div>
        <div class="row mb-3">
          <div class="col-lg-3 col-md-4 label text-muted">Phone</div>
          <div class="col-lg-9 col-md-8"><?= esc($user['phone']) ?></div>
        </div>
        <div class="row mb-3">
          <div class="col-lg-3 col-md-4 label text-muted">Email</div>
          <div class="col-lg-9 col-md-8"><?= esc($user['email']) ?></div>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
