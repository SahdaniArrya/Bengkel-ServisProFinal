<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ServiceModel;
use App\Models\BookingModel;
use App\Models\ScheduleModel;

/**
 * API Controller - Webservice Server (Milestone 6)
 * 
 * Menyediakan RESTful API endpoint untuk data bengkel.
 * Semua endpoint dilindungi oleh API Key (header: X-API-KEY)
 * 
 * Endpoint tersedia:
 *  GET  /api/services              - Daftar semua layanan bengkel
 *  GET  /api/services/{id}         - Detail layanan tertentu
 *  GET  /api/bookings/{id}         - Detail booking tertentu
 *  POST /api/bookings              - Buat booking baru via API
 *  GET  /api-docs                  - Dokumentasi API (publik)
 */
class BengkelApiController extends ResourceController
{
    protected $format = 'json';

    protected ServiceModel  $serviceModel;
    protected BookingModel  $bookingModel;
    protected ScheduleModel $scheduleModel;

    public function __construct()
    {
        $this->serviceModel  = new ServiceModel();
        $this->bookingModel  = new BookingModel();
        $this->scheduleModel = new ScheduleModel();
    }

    // ========================================================
    // GET /api/services
    // Mengembalikan daftar semua layanan aktif bengkel
    // ========================================================
    public function services()
    {
        $services = $this->serviceModel->getActiveWithRating();

        if (empty($services)) {
            return $this->respond([
                'status'  => 200,
                'message' => 'Tidak ada layanan tersedia saat ini.',
                'data'    => [],
            ]);
        }

        // Format data agar lebih bersih untuk API consumer
        $formatted = array_map(function ($s) {
            return [
                'id'            => (int) $s['id'],
                'name'          => $s['name'],
                'description'   => $s['description'],
                'price'         => (int) $s['price'],
                'duration_min'  => (int) $s['duration_min'],
                'photo_url'     => base_url('public/uploads/services/' . $s['photo']),
                'avg_rating'    => round((float) $s['avg_rating'], 1),
                'total_reviews' => (int) $s['total_reviews'],
            ];
        }, $services);

        return $this->respond([
            'status'  => 200,
            'message' => 'Berhasil mengambil data layanan.',
            'total'   => count($formatted),
            'data'    => $formatted,
        ]);
    }

    // ========================================================
    // GET /api/services/{id}
    // Mengembalikan detail satu layanan berdasarkan ID
    // ========================================================
    public function serviceDetail($id)
    {
        $service = $this->serviceModel->find($id);

        if (!$service || !$service['is_active']) {
            return $this->failNotFound('Layanan dengan ID ' . $id . ' tidak ditemukan atau tidak aktif.');
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Berhasil mengambil detail layanan.',
            'data'    => [
                'id'           => (int) $service['id'],
                'name'         => $service['name'],
                'description'  => $service['description'],
                'price'        => (int) $service['price'],
                'duration_min' => (int) $service['duration_min'],
                'photo_url'    => base_url('public/uploads/services/' . $service['photo']),
                'is_active'    => (bool) $service['is_active'],
            ],
        ]);
    }

    // ========================================================
    // GET /api/bookings/{id}
    // Mengembalikan detail booking berdasarkan ID booking
    // ========================================================
    public function bookingDetail($id)
    {
        $booking = $this->bookingModel->getWithDetails($id);

        if (!$booking) {
            return $this->failNotFound('Booking dengan ID ' . $id . ' tidak ditemukan.');
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Berhasil mengambil detail booking.',
            'data'    => [
                'id'             => (int) $booking['id'],
                'user_name'      => $booking['user_name'],
                'service_name'   => $booking['service_name'],
                'price'          => (int) $booking['price'],
                'available_date' => $booking['available_date'],
                'slot_time'      => $booking['slot_time'],
                'staff_name'     => $booking['staff_name'] ?? 'Belum ditugaskan',
                'status'         => $booking['status'],
                'payment_status' => $booking['payment_status'] ?? 'unpaid',
                'notes'          => $booking['notes'],
                'created_at'     => $booking['created_at'],
            ],
        ]);
    }

    // ========================================================
    // POST /api/bookings
    // Membuat booking baru via API
    // Body (JSON): { user_id, service_id, schedule_id, notes }
    // ========================================================
    public function createBooking()
    {
        $rules = [
            'user_id'     => 'required|integer|greater_than[0]',
            'service_id'  => 'required|integer|greater_than[0]',
            'schedule_id' => 'required|integer|greater_than[0]',
        ];

        $input = $this->request->getJSON(true) ?? $this->request->getPost();

        if (!$this->validateData($input, $rules)) {
            return $this->fail([
                'status'  => 400,
                'error'   => 'Validasi gagal',
                'message' => $this->validator->getErrors(),
            ], 400);
        }

        $userId     = (int) $input['user_id'];
        $serviceId  = (int) $input['service_id'];
        $scheduleId = (int) $input['schedule_id'];
        $notes      = $input['notes'] ?? '';

        // Validasi layanan
        $service = $this->serviceModel->find($serviceId);
        if (!$service || !$service['is_active']) {
            return $this->fail('Layanan tidak ditemukan atau tidak aktif.', 404);
        }

        // Validasi jadwal & kapasitas
        $schedule = $this->scheduleModel->find($scheduleId);
        if (!$schedule) {
            return $this->fail('Jadwal tidak ditemukan.', 404);
        }
        if ($schedule['booked_count'] >= $schedule['capacity']) {
            return $this->fail('Slot jadwal ini sudah penuh.', 409);
        }

        // Cek duplikat booking
        $existing = $this->bookingModel
            ->where('user_id', $userId)
            ->where('schedule_id', $scheduleId)
            ->where('status !=', 'cancelled')
            ->first();
        if ($existing) {
            return $this->fail('User sudah memiliki booking aktif di jadwal yang sama.', 409);
        }

        // Simpan booking
        $bookingId = $this->bookingModel->insert([
            'user_id'     => $userId,
            'service_id'  => $serviceId,
            'schedule_id' => $scheduleId,
            'notes'       => $notes,
            'status'      => 'pending',
        ]);

        // Update booked_count pada jadwal
        $this->scheduleModel->set('booked_count', 'booked_count + 1', false)->update($scheduleId);

        return $this->respondCreated([
            'status'     => 201,
            'message'    => 'Booking berhasil dibuat! Menunggu konfirmasi bengkel.',
            'booking_id' => $bookingId,
        ]);
    }

    // ========================================================
    // GET /api-docs
    // Dokumentasi API (publik, tanpa API Key) - untuk demo presentasi
    // ========================================================
    public function docs()
    {
        return $this->respond([
            'app'         => 'Bengkel ServisPro API',
            'version'     => '1.0.0',
            'description' => 'RESTful API untuk sistem manajemen bengkel servis kendaraan.',
            'base_url'    => base_url('api'),
            'auth'        => 'Semua endpoint membutuhkan header: X-API-KEY',
            'valid_keys'  => ['BENGKEL-SECRET-KEY-2024', 'MOBILE-APP-KEY-BENGKEL'],
            'endpoints'   => [
                [
                    'method'      => 'GET',
                    'url'         => base_url('api/services'),
                    'description' => 'Mendapatkan daftar semua layanan bengkel yang aktif beserta rating.',
                    'headers'     => ['X-API-KEY: BENGKEL-SECRET-KEY-2024'],
                    'response'    => 'JSON array layanan bengkel.',
                ],
                [
                    'method'      => 'GET',
                    'url'         => base_url('api/services/{id}'),
                    'description' => 'Mendapatkan detail layanan berdasarkan ID.',
                    'headers'     => ['X-API-KEY: BENGKEL-SECRET-KEY-2024'],
                    'response'    => 'JSON object detail layanan.',
                ],
                [
                    'method'      => 'GET',
                    'url'         => base_url('api/bookings/{id}'),
                    'description' => 'Mendapatkan detail booking berdasarkan ID booking.',
                    'headers'     => ['X-API-KEY: BENGKEL-SECRET-KEY-2024'],
                    'response'    => 'JSON object detail booking.',
                ],
                [
                    'method'      => 'POST',
                    'url'         => base_url('api/bookings'),
                    'description' => 'Membuat booking baru via API.',
                    'headers'     => ['X-API-KEY: BENGKEL-SECRET-KEY-2024', 'Content-Type: application/json'],
                    'body'        => ['user_id' => 1, 'service_id' => 1, 'schedule_id' => 1, 'notes' => 'Opsional'],
                    'response'    => 'JSON konfirmasi booking berhasil.',
                ],
            ],
        ]);
    }
}
