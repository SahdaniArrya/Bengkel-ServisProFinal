<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<style>
  .help-accordion .accordion-item {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    margin-bottom: 20px;
    overflow: hidden;
    transition: transform 0.3s ease;
  }

  .help-accordion .accordion-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
  }

  .help-accordion .accordion-button {
    font-weight: 600;
    color: #444;
    background-color: #fff;
    padding: 20px;
    border-radius: 12px !important;
    font-size: 1.05rem;
  }

  .help-accordion .accordion-button:not(.collapsed) {
    color: #dc3545;
    background-color: #fff5f5;
    box-shadow: none;
  }

  .help-accordion .accordion-button::after {
    background-size: 1.25rem;
  }

  .help-accordion .accordion-body {
    background-color: #fff;
    padding: 20px;
    color: #555;
    line-height: 1.7;
    border-top: 1px solid #f8f9fa;
  }

  .help-header-card {
    background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);
    color: white;
    border-radius: 15px;
    padding: 40px 20px;
    margin-bottom: 30px;
    text-align: center;
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.25);
    position: relative;
    overflow: hidden;
  }

  .help-header-card::after {
    content: "";
    position: absolute;
    top: -50px;
    right: -50px;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
  }

  .help-header-card h2 {
    font-weight: 700;
    margin-bottom: 10px;
    position: relative;
    z-index: 1;
  }

  .help-header-card p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
    position: relative;
    z-index: 1;
  }

  .support-card {
    background: #fff;
    border-radius: 15px;
    padding: 35px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.04);
    border-top: 4px solid #dc3545;
    transition: all 0.3s ease;
    height: 100%;
  }
  
  .support-card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
  }

  .support-icon {
    font-size: 3.5rem;
    color: #28a745;
    margin-bottom: 15px;
    display: inline-block;
    padding: 20px;
    background: rgba(40, 167, 69, 0.1);
    border-radius: 50%;
    width: 100px;
    height: 100px;
    line-height: 60px;
  }
</style>

<div class="row">
  <div class="col-12">
    <div class="help-header-card">
      <i class="bi bi-life-preserver" style="font-size: 3rem; margin-bottom: 15px; display: inline-block; position: relative; z-index: 1;"></i>
      <h2>Pusat Bantuan & Layanan</h2>
      <p>Temukan jawaban untuk pertanyaan umum seputar sistem aplikasi ini</p>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-8">
    <div class="card shadow-none bg-transparent mb-0 border-0">
      <div class="card-body p-0">
        <h5 class="mb-4 fw-bold text-dark d-flex align-items-center">
          <i class="bi bi-question-circle text-danger me-2 fs-4"></i> Frequently Asked Questions
        </h5>

        <div class="accordion help-accordion" id="helpAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true">
                <i class="bi bi-calendar-check text-danger me-2"></i> Bagaimana cara menyetujui / mengonfirmasi booking pelanggan?
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
              <div class="accordion-body">
                Buka menu <strong>Kelola Booking</strong> di sidebar kiri, lalu cari pesanan berstatus <span class="badge bg-warning text-dark">Pending</span>. Klik tombol <strong>Detail</strong>, kemudian pilih mekanik/staff yang tersedia, lalu klik tombol <strong class="text-success">Konfirmasi Booking</strong>.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                <i class="bi bi-person-x text-danger me-2"></i> Bagaimana cara menonaktifkan akun pengguna atau staff?
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
              <div class="accordion-body">
                Untuk staff, masuk ke menu <strong>Kelola Staff</strong> lalu klik tombol <span class="badge bg-warning text-dark"><i class="bi bi-power"></i></span> warna kuning di kolom aksi. Untuk pengguna lain, masuk ke menu <strong>Pengguna</strong>, klik tombol edit (pensil), lalu pilih tombol <strong class="text-danger">Nonaktifkan Pengguna</strong> di halaman detail.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                <i class="bi bi-person-lines-fill text-danger me-2"></i> Bagaimana jika ingin merubah data profil saya?
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
              <div class="accordion-body">
                Klik foto profil Anda di pojok kanan atas, pilih <strong><i class="bi bi-gear"></i> Account Settings</strong>. Isi form dengan data terbaru Anda, pastikan email atau nomor telepon sudah benar, lalu tekan tombol <strong>Save Changes</strong>.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="support-card text-center mt-4 mt-lg-0">
      <div class="support-icon">
        <i class="bi bi-whatsapp"></i>
      </div>
      <h4 class="fw-bold text-dark mb-3">Butuh Bantuan Lain?</h4>
      <p class="text-muted mb-4">Jika Anda tidak menemukan jawaban dari pertanyaan Anda, jangan ragu untuk menghubungi tim support kami melalui WhatsApp.</p>
      <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-success btn-lg w-100 rounded-pill shadow-sm" style="font-weight: 600; padding: 12px 20px;">
        Hubungi Support
      </a>
      
      <hr class="my-4" style="border-color: #eee;">
      
      <div class="text-start bg-light p-3 rounded-3">
        <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-clock text-danger me-2"></i> Jam Operasional</h6>
        <p class="text-muted small mb-0" style="line-height: 1.6;">
          <span class="d-block"><strong>Senin - Jumat:</strong> 08:00 - 17:00 WIB</span>
          <span class="d-block"><strong>Sabtu:</strong> 08:00 - 14:00 WIB</span>
        </p>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
