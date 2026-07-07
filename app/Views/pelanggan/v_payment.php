<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm mb-4" style="border-top: 3px solid #dc3545;">
      <div class="card-body pt-4">
        <h5 class="card-title pb-2" style="border-bottom: 2px solid #dc3545; display: inline-block; padding-bottom: 5px; margin-bottom: 25px; color: #000; font-weight: 700;">
          Invoice Pembayaran (UAS)
        </h5>

        <div class="row mb-3">
          <div class="col-6 text-muted">ID Booking</div>
          <div class="col-6 text-end fw-bold text-dark">#<?= esc($booking['id']) ?></div>
        </div>

        <div class="row mb-3">
          <div class="col-6 text-muted">Layanan Servis</div>
          <div class="col-6 text-end fw-bold text-danger"><?= esc($booking['service_name']) ?></div>
        </div>

        <div class="row mb-3">
          <div class="col-6 text-muted">Teknisi / Mekanik</div>
          <div class="col-6 text-end text-dark"><?= esc($booking['staff_name'] ?? '-') ?></div>
        </div>

        <div class="row mb-3">
          <div class="col-6 text-muted">Tanggal Booking</div>
          <div class="col-6 text-end text-dark"><?= date('d F Y', strtotime($booking['available_date'])) ?></div>
        </div>

        <div class="row mb-3">
          <div class="col-6 text-muted">Waktu / Jam</div>
          <div class="col-6 text-end text-dark"><?= substr($booking['slot_time'], 0, 5) ?> WIB</div>
        </div>

        <hr class="my-4">

        <div class="row mb-4 align-items-center">
          <div class="col-6"><h5 class="fw-bold text-dark mb-0">Total Pembayaran</h5></div>
          <div class="col-6 text-end"><h4 class="fw-bold text-danger mb-0">Rp <?= number_format($booking['price'], 0, ',', '.') ?></h4></div>
        </div>

        <?php if (!empty($snapToken)): ?>
            <div class="alert alert-info border-0 small">
              <i class="bi bi-info-circle me-1"></i> Klik tombol di bawah ini untuk memilih metode pembayaran melalui Midtrans yang aman.
            </div>

            <div class="d-grid mt-4">
              <button id="pay-button" class="btn btn-danger py-3 fw-bold" style="font-size: 16px;">
                <i class="bi bi-credit-card me-2"></i> Bayar Sekarang
              </button>
              <a href="/pelanggan/riwayat" class="btn btn-link text-muted mt-2 text-center small">Kembali</a>
            </div>
            
            <!-- Tambahkan form tersembunyi untuk redirect jika sukses -->
            <form id="payment-success-form" action="/pelanggan/riwayat" method="get" class="d-none">
                <!-- Kita redirect ke riwayat saja dan set session success -->
            </form>
        <?php else: ?>
            <div class="alert alert-danger border-0 small">
              <i class="bi bi-exclamation-triangle me-1"></i> Gagal menghubungkan ke server pembayaran. Pastikan API Key diatur dengan benar di server.
            </div>
            <div class="d-grid mt-4">
              <a href="/pelanggan/riwayat" class="btn btn-outline-secondary py-3 fw-bold">Kembali ke Riwayat</a>
            </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<?php if (!empty($snapToken)): ?>
<!-- Menggunakan script Snap.js dari Sandbox Midtrans -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= esc($clientKey) ?>"></script>
<script type="text/javascript">
  document.getElementById('pay-button').onclick = function(){
    snap.pay('<?= $snapToken ?>', {
      onSuccess: function(result){
        // Kirim data hasil pembayaran langsung ke server via POST form
        // Ini menghindari CURL timeout karena kita tidak perlu query ulang ke Midtrans
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/pelanggan/booking/simpan-pembayaran/<?= $booking['id'] ?>';

        // CSRF Token
        var csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '<?= csrf_token() ?>';
        csrf.value = '<?= csrf_hash() ?>';
        form.appendChild(csrf);

        // Data dari Midtrans
        var fields = ['order_id','transaction_status','payment_type','transaction_id'];
        fields.forEach(function(key) {
          var input = document.createElement('input');
          input.type = 'hidden';
          input.name = key;
          input.value = result[key] ?? '';
          form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
      },
      onPending: function(result){
        alert("Menunggu pembayaran Anda! Selesaikan di aplikasi/bank Anda.");
      },
      onError: function(result){
        alert("Pembayaran Gagal! Silakan coba lagi.");
      },
      onClose: function(){
        // User menutup popup
      }
    });
  };
</script>
<?php endif; ?>

<?= $this->endSection() ?>
