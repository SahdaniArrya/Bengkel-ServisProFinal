<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">Daftar Staff</h5>
            <a href="<?= base_url('admin/staff/create') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Tambah Staff</a>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Spesialisasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($staffs)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Data staff masih kosong</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($staffs as $index => $staff): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($staff['name']) ?></td>
                            <td><?= esc($staff['phone']) ?></td>
                            <td><?= esc($staff['specialization']) ?></td>
                            <td>
                                <?php if($staff['is_active']): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/staff/edit/' . $staff['id']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="<?= base_url('admin/staff/delete/' . $staff['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus staff ini? Akun login juga akan dihapus.')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
