<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-body pt-3">
    <h5 class="card-title">Edit Profile</h5>
    <form>
      <div class="row mb-3">
        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
        <div class="col-md-8 col-lg-9">
          <input name="fullName" type="text" class="form-control" id="fullName" value="<?= esc($user['name']) ?>">
        </div>
      </div>
      
      <div class="row mb-3">
        <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
        <div class="col-md-8 col-lg-9">
          <input name="phone" type="text" class="form-control" id="Phone" value="<?= esc($user['phone']) ?>">
        </div>
      </div>

      <div class="row mb-3">
        <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
        <div class="col-md-8 col-lg-9">
          <input name="email" type="email" class="form-control" id="Email" value="<?= esc($user['email']) ?>">
        </div>
      </div>

      <div class="text-center">
        <button type="button" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
