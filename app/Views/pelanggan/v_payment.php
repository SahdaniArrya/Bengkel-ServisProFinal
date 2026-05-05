<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Selesaikan Pembayaran</h5>
            </div>
            <div class="card-body">
                <p><strong>Layanan:</strong> <?= esc($booking['service_name']) ?></p>
                <p><strong>Harga:</strong> Rp <?= number_format($booking['price'], 0, ',', '.') ?></p>
                <p><strong>Tanggal Booking:</strong> <?= date('d M Y', strtotime($booking['available_date'])) ?> <?= substr($booking['slot_time'], 0, 5) ?> WIB</p>
                <hr>
                <p class="text-center">Silakan selesaikan pembayaran Anda menggunakan berbagai metode seperti Transfer Bank, QRIS, GoPay, dan lainnya.</p>
                <div class="text-center">
                    <button id="pay-button" class="btn btn-success btn-lg">Bayar Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' ?>" data-client-key="<?= $clientKey ?>"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        snap.pay('<?= $snapToken ?>', {
            onSuccess: function (result) {
                window.location.href = '/pelanggan/payment/finish?order_id=' + result.order_id + '&transaction_status=' + result.transaction_status;
            },
            onPending: function (result) {
                window.location.href = '/pelanggan/payment/finish?order_id=' + result.order_id + '&transaction_status=' + result.transaction_status;
            },
            onError: function (result) {
                alert('Pembayaran gagal!');
            },
            onClose: function () {
                alert('Anda menutup popup tanpa menyelesaikan pembayaran');
            }
        });
    };
</script>

<?= $this->endSection() ?>
