# Bengkel ServisPro — Sistem Booking Layanan Motor

Project UTS Pemrograman Web Lanjut — CodeIgniter 4  
Universitas Dian Nuswantoro

---

## Tech Stack

- **Framework**: CodeIgniter 4
- **Database**: MySQL
- **Frontend**: Bootstrap 5 + Bootstrap Icons
- **Payment**: Midtrans Sandbox (sprint berikutnya)

---

## Cara Instalasi

### 1. Clone / ekstrak project ke folder 
```bash
# atau langsung copy folder project
```

### 2. Install dependencies
```bash
composer install
```

### 3. Konfigurasi .env
```bash
cp env .env
```

Edit `.env`:
```
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = bengkel_servispro
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```

### 4. Buat database
```sql
CREATE DATABASE bengkel_servispro CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

### 5. Jalankan migration & seeder
```bash
php spark migrate
php spark db:seed DatabaseSeeder
```
### 6. Jalankan server lokal
```bash
php spark serve
```

Akses di: **http://localhost:8080**

---

## Akun Demo

| Role      | Email                    | Password  |
|-----------|--------------------------|-----------|
| Admin     | admin@bengkel.com        | admin123  |
| Staff     | staff@bengkel.com        | staff123  |
| Pelanggan | pelanggan@gmail.com      | user123   |

---

## Fitur yang Sudah Diimplementasi (UTS)

### Sebelum Batas UTS:
- [x] ERD database (7 tabel, 3NF, relasi lengkap)
- [x] Migration semua tabel dengan foreign key
- [x] Seeder realistis (users, staff, services, schedules, bookings)
- [x] Autentikasi: login, register, logout
- [x] Multi-role: admin, staff, pelanggan
- [x] CI4 Filter: AuthFilter (proteksi route per role), GuestFilter
- [x] CRUD Layanan (admin) dengan upload foto
- [x] Kelola Booking (admin): konfirmasi, tolak, filter status
- [x] Booking alur pelanggan: pilih layanan → pilih slot → konfirmasi → simpan
- [x] Riwayat & batalkan booking (pelanggan)
- [x] Dashboard admin dengan statistik

### Sprint Berikutnya:
- [ ] Integrasi Midtrans Sandbox
- [ ] Notifikasi email (PHPMailer)
- [ ] Staff dashboard & update status
- [ ] API endpoint publik
- [ ] Review & rating pelanggan

---

## Struktur Route

```
GET  /                          → Halaman publik
GET  /auth/login                → Login (guest only)
POST /auth/login                → Proses login
GET  /auth/register             → Register (guest only)
POST /auth/register             → Proses register
GET  /auth/logout               → Logout

GET  /admin/dashboard           → Dashboard admin
GET  /admin/services            → Daftar layanan
GET  /admin/services/create     → Form tambah layanan
POST /admin/services/store      → Simpan layanan baru
GET  /admin/services/edit/{id}  → Form edit layanan
POST /admin/services/update/{id}→ Update layanan
GET  /admin/services/delete/{id}→ Hapus layanan
GET  /admin/bookings            → Daftar booking
GET  /admin/bookings/{id}       → Detail booking
POST /admin/bookings/confirm/{id}→ Konfirmasi booking

GET  /pelanggan/booking         → Pilih layanan
GET  /pelanggan/booking/jadwal/{id} → Pilih jadwal
GET  /pelanggan/booking/konfirmasi  → Konfirmasi booking
POST /pelanggan/booking/store   → Simpan booking
GET  /pelanggan/booking/cancel/{id} → Batalkan booking
GET  /pelanggan/riwayat         → Riwayat booking


```

---

---
