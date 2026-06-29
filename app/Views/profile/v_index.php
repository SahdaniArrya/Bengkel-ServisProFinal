<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="row">
  <!-- Left Side: Profile Picture and Info -->
  <div class="col-lg-4 mb-4">
    <div class="card h-100 text-center" style="border-top: 3px solid #dc3545;">
      <div class="card-body pt-4">
        <div class="d-flex flex-column align-items-center">
          <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px; border: 1px solid #f5c2c7; background-color: #f8d7da !important;">
            <i class="bi bi-person-fill" style="font-size: 60px; color: #dc3545;"></i>
          </div>
          <h4 class="text-dark fw-bold mb-1"><?= esc($user['name']) ?></h4>
          <p class="text-muted mb-0"><?= ucfirst(esc($user['role'])) ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Side: Details -->
  <div class="col-lg-8 mb-4">
    <div class="card h-100" style="border-top: 3px solid #dc3545;">
      <div class="card-body pt-4">
        <h5 class="card-title pb-2" style="border-bottom: 2px solid #dc3545; display: inline-block; padding-bottom: 5px; margin-bottom: 25px; color: #000; font-weight: 700;">
          Profile Details
        </h5>

        <div class="row mb-3 pb-2 border-bottom">
          <div class="col-sm-3 text-muted">Full Name</div>
          <div class="col-sm-9 text-dark fw-semibold"><?= esc($user['name']) ?></div>
        </div>

        <div class="row mb-3 pb-2 border-bottom">
          <div class="col-sm-3 text-muted">Role</div>
          <div class="col-sm-9 text-dark"><?= ucfirst(esc($user['role'])) ?></div>
        </div>

        <div class="row mb-3 pb-2 border-bottom">
          <div class="col-sm-3 text-muted">Phone</div>
          <div class="col-sm-9 text-dark"><?= esc($user['phone']) ?></div>
        </div>

        <div class="row mb-3 pb-2 border-bottom">
          <div class="col-sm-3 text-muted">Email</div>
          <div class="col-sm-9 text-dark"><?= esc($user['email']) ?></div>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
