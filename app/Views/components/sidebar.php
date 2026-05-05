<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <?php if (session()->get('role') == 'admin'): ?>
      <li class="nav-item">
        <a class="nav-link <?= (uri_string() == 'admin/dashboard') ? '' : 'collapsed' ?>" href="/admin/dashboard">
          <i class="bi bi-speedometer2"></i><span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= (str_starts_with(uri_string(), 'admin/bookings')) ? '' : 'collapsed' ?>" href="/admin/bookings">
          <i class="bi bi-calendar-check"></i><span>Kelola Booking</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= (str_starts_with(uri_string(), 'admin/services')) ? '' : 'collapsed' ?>" href="/admin/services">
          <i class="bi bi-tools"></i><span>Kelola Layanan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= (str_starts_with(uri_string(), 'admin/staff')) ? '' : 'collapsed' ?>" href="/admin/staff">
          <i class="bi bi-people"></i><span>Kelola Staff</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= (str_starts_with(uri_string(), 'admin/users')) ? '' : 'collapsed' ?>" href="/admin/users">
          <i class="bi bi-person-lines-fill"></i><span>Pengguna</span>
        </a>
      </li>

    <?php elseif (session()->get('role') == 'staff'): ?>
      <li class="nav-item">
        <a class="nav-link <?= (uri_string() == 'staff/dashboard') ? '' : 'collapsed' ?>" href="/staff/dashboard">
          <i class="bi bi-speedometer2"></i><span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= (uri_string() == 'staff/jadwal') ? '' : 'collapsed' ?>" href="/staff/jadwal">
          <i class="bi bi-calendar3"></i><span>Jadwal Saya</span>
        </a>
      </li>

    <?php else: ?>
      <li class="nav-item">
        <a class="nav-link <?= (uri_string() == 'pelanggan/booking') ? '' : 'collapsed' ?>" href="/pelanggan/booking">
          <i class="bi bi-plus-circle"></i><span>Booking Servis</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= (uri_string() == 'pelanggan/riwayat') ? '' : 'collapsed' ?>" href="/pelanggan/riwayat">
          <i class="bi bi-clock-history"></i><span>Riwayat Booking</span>
        </a>
      </li>
    <?php endif; ?>

  </ul>
</aside>
