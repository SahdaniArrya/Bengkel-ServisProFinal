<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Beri Ulasan & Rating</h5>
            </div>
            <div class="card-body">
                <p><strong>Layanan:</strong> <?= esc($booking['service_name']) ?></p>
                <p><strong>Teknisi:</strong> <?= esc($booking['staff_name']) ?></p>
                <p><strong>Tanggal Selesai:</strong> <?= date('d M Y', strtotime($booking['available_date'])) ?></p>
                <hr>
                <form action="/pelanggan/review/store/<?= $booking['id'] ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Rating (1-5 Bintang)</label>
                        <select name="rating" class="form-select" required>
                            <option value="">Pilih Rating...</option>
                            <option value="5">5 - Sangat Memuaskan</option>
                            <option value="4">4 - Memuaskan</option>
                            <option value="3">3 - Cukup</option>
                            <option value="2">2 - Kurang</option>
                            <option value="1">1 - Sangat Kurang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ulasan / Komentar</label>
                        <textarea name="comment" class="form-control" rows="4" placeholder="Ceritakan pengalaman Anda..."></textarea>
                    </div>
                    <div class="text-end">
                        <a href="/pelanggan/riwayat" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
