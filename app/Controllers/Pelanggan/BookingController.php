<?php

namespace App\Controllers\Pelanggan;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\ServiceModel;
use App\Models\ScheduleModel;
use App\Models\PaymentModel;
use App\Models\ReviewModel;
use App\Libraries\WeatherService;
use App\Libraries\NotificationService;

class BookingController extends BaseController
{
    protected $bookingModel, $serviceModel, $scheduleModel, $paymentModel, $reviewModel;
    public function __construct()
    {
        $this->bookingModel  = new BookingModel();
        $this->serviceModel  = new ServiceModel();
        $this->scheduleModel = new ScheduleModel();
        $this->paymentModel  = new PaymentModel();
        $this->reviewModel   = new ReviewModel();
    }

    public function index()
    {
        return view('pelanggan/v_pilih_layanan', ['title'=>'Booking Servis','services'=>$this->serviceModel->getActiveWithRating()]);
    }

    public function pilihJadwal($serviceId)
    {
        $service = $this->serviceModel->find($serviceId);
        if (!$service || !$service['is_active']) return redirect()->to('/pelanggan/booking')->with('error','Layanan tidak tersedia.');
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        return view('pelanggan/v_pilih_jadwal', ['title'=>'Pilih Jadwal','service'=>$service,'schedules'=>$this->scheduleModel->getAvailableByService($serviceId, $date),'date'=>$date]);
    }

    public function konfirmasi()
    {
        $service  = $this->serviceModel->find($this->request->getGet('service_id'));
        $schedule = $this->scheduleModel->find($this->request->getGet('schedule_id'));
        if (!$service || !$schedule) return redirect()->to('/pelanggan/booking')->with('error','Data tidak valid.');
        if ($schedule['booked_count'] >= $schedule['capacity']) return redirect()->back()->with('error','Slot ini sudah penuh.');
        return view('pelanggan/v_konfirmasi', ['title'=>'Konfirmasi Booking','service'=>$service,'schedule'=>$schedule]);
    }

    public function store()
    {
        $serviceId  = $this->request->getPost('service_id');
        $scheduleId = $this->request->getPost('schedule_id');
        $userId     = session()->get('user_id');
        $service    = $this->serviceModel->find($serviceId);
        $schedule   = $this->scheduleModel->find($scheduleId);
        if (!$service || !$schedule || $schedule['booked_count'] >= $schedule['capacity']) {
            return redirect()->to('/pelanggan/booking')->with('error','Booking gagal. Slot tidak tersedia.');
        }
        $existing = $this->bookingModel->where('user_id',$userId)->where('schedule_id',$scheduleId)->where('status !=','cancelled')->first();
        if ($existing) return redirect()->back()->with('error','Anda sudah memiliki booking di slot ini.');
        
        $this->bookingModel->insert([
            'user_id' => $userId,
            'service_id' => $serviceId,
            'schedule_id' => $scheduleId,
            'notes' => $this->request->getPost('notes'),
            'status' => 'pending'
        ]);
        
        $this->scheduleModel->set('booked_count','booked_count + 1',false)->update($scheduleId);
        
        // Send email notification
        $userName = session()->get('user_name') ?? 'Pelanggan';
        $userEmail = session()->get('user_email'); // assuming this is set, if not we could fetch from UserModel
        if ($userEmail) {
            $notif = new NotificationService();
            $notif->sendBookingConfirmation($userEmail, $userName, $service['name'], $schedule['available_date'], $schedule['slot_time']);
        }

        return redirect()->to('/pelanggan/riwayat')->with('success','Booking berhasil! Menunggu konfirmasi bengkel.');
    }

    public function riwayat()
    {
        $weatherService = new WeatherService();
        return view('pelanggan/v_riwayat', [
            'title'    => 'Riwayat Booking',
            'bookings' => $this->bookingModel->getByUser(session()->get('user_id')),
            'weather'  => $weatherService->getWeather(), // Data cuaca BMKG (Milestone 5)
        ]);
    }


    public function cancel($id)
    {
        $booking = $this->bookingModel->find($id);
        if (!$booking || $booking['user_id'] != session()->get('user_id')) return redirect()->to('/pelanggan/riwayat')->with('error','Booking tidak ditemukan.');
        if ($booking['status'] !== 'pending') return redirect()->back()->with('error','Booking yang sudah dikonfirmasi tidak dapat dibatalkan.');
        $this->bookingModel->update($id, ['status'=>'cancelled']);
        $this->scheduleModel->set('booked_count','booked_count - 1',false)->update($booking['schedule_id']);
        return redirect()->to('/pelanggan/riwayat')->with('success','Booking berhasil dibatalkan.');
    }

    public function payment($id)
    {
        $booking = $this->bookingModel->getWithDetails($id);
        if (!$booking || $booking['user_id'] != session()->get('user_id')) {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Booking tidak ditemukan.');
        }

        // Cek status booking (hanya boleh jika dikonfirmasi atau sedang dikerjakan)
        if ($booking['status'] !== 'confirmed' && $booking['status'] !== 'in_progress') {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Pembayaran hanya dapat dilakukan saat booking dikonfirmasi atau dalam proses pengerjaan.');
        }

        // Cek jika sudah bayar
        if ($booking['payment_status'] === 'paid') {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Booking ini sudah dibayar.');
        }

        // Cari atau buat Order ID untuk pembayaran ini
        $payment = $this->paymentModel->getByBooking($id);
        $orderId = '';
        if ($payment) {
            $orderId = $payment['order_id'];
            $snapToken = $payment['snap_token'];
        } else {
            $orderId = 'BKL-' . time() . '-' . $id;
            // Generate snap token via Midtrans API
            $snapToken = $this->_getSnapToken($orderId, $booking['price'], $booking);
            
            if ($snapToken) {
                // Simpan ke database
                $this->paymentModel->insert([
                    'booking_id' => $id,
                    'order_id'   => $orderId,
                    'amount'     => $booking['price'],
                    'status'     => 'pending',
                    'snap_token' => $snapToken
                ]);
            }
        }

        $data = [
            'title'     => 'Pembayaran Booking',
            'booking'   => $booking,
            'snapToken' => $snapToken ?? '',
            'clientKey' => env('MIDTRANS_CLIENT_KEY', '')
        ];
        return view('pelanggan/v_payment', $data);
    }

    /**
     * Helper to get Snap Token using native CI4 CURLRequest
     */
    private function _getSnapToken($orderId, $amount, $bookingData)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY', '');
        $isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        $url = $isProduction ? 'https://app.midtrans.com/snap/v1/transactions' : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $client = \Config\Services::curlrequest([
            'baseURI' => $url,
            'timeout' => 10,
            'http_errors' => false
        ]);

        $payload = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $amount,
            ],
            'customer_details' => [
                'first_name' => $bookingData['user_name'],
                'email'      => $bookingData['user_email'] ?? 'pelanggan@example.com',
                'phone'      => $bookingData['user_phone']
            ],
            'item_details' => [
                [
                    'id'       => 'SRV-' . $bookingData['service_id'],
                    'price'    => (int) $amount,
                    'quantity' => 1,
                    'name'     => substr($bookingData['service_name'], 0, 50)
                ]
            ]
        ];

        $response = $client->post('', [
            'headers' => [
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($serverKey . ':')
            ],
            'json' => $payload
        ]);

        if ($response->getStatusCode() === 201) {
            $body = json_decode($response->getBody(), true);
            return $body['token'] ?? null;
        }

        // Debug: log error response
        log_message('error', '[Midtrans] Gagal get snap token. Status: ' . $response->getStatusCode() . ' Body: ' . $response->getBody());
        return null;
    }

    /**
     * Cek status pembayaran langsung ke Midtrans (Alternatif 1 - Tanpa Ngrok)
     * Dipanggil setelah popup Midtrans sukses di sisi client.
     */
    public function checkPayment($bookingId)
    {
        $booking = $this->bookingModel->getWithDetails($bookingId);
        if (!$booking || $booking['user_id'] != session()->get('user_id')) {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Booking tidak ditemukan.');
        }

        $payment = $this->paymentModel->getByBooking($bookingId);
        if (!$payment) {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // Query status ke Midtrans
        $serverKey    = env('MIDTRANS_SERVER_KEY', '');
        $isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        $baseUrl      = $isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';

        $client = \Config\Services::curlrequest(['http_errors' => false, 'timeout' => 10]);

        $response = $client->get($baseUrl . '/v2/' . $payment['order_id'] . '/status', [
            'headers' => [
                'Accept'        => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($serverKey . ':'),
            ]
        ]);

        $body = json_decode($response->getBody(), true);

        $transactionStatus = $body['transaction_status'] ?? 'unknown';

        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            // SUKSES - update database
            $this->paymentModel->update($payment['id'], [
                'status'       => 'paid',
                'payment_type' => $body['payment_type'] ?? 'online',
                'paid_at'      => date('Y-m-d H:i:s'),
            ]);

            // Kirim email notifikasi sukses (jangan crash app kalau email gagal)
            try {
                $notif = new NotificationService();
                if (!empty($booking['user_email'])) {
                    $notif->sendPaymentSuccess(
                        $booking['user_email'],
                        $booking['user_name'],
                        $booking['service_name'],
                        $payment['amount'],
                        $payment['order_id']
                    );
                }
            } catch (\Throwable $e) {
                log_message('error', '[NotificationService] Gagal kirim email: ' . $e->getMessage());
            }

            return redirect()->to('/pelanggan/riwayat')->with('success', '✅ Pembayaran berhasil! Status booking kamu sudah diperbarui.');

        } elseif ($transactionStatus === 'pending') {
            return redirect()->to('/pelanggan/riwayat')->with('error', '⏳ Pembayaran kamu masih pending. Selesaikan pembayarannya dan coba cek lagi.');
        } else {
            return redirect()->to('/pelanggan/riwayat')->with('error', '❌ Pembayaran gagal atau kadaluwarsa (status: ' . $transactionStatus . '). Silakan coba lagi.');
        }
    }

    public function payProcess($id)
    {
        $booking = $this->bookingModel->getWithDetails($id);
        if (!$booking || $booking['user_id'] != session()->get('user_id')) {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Booking tidak ditemukan.');
        }

        // Cek status booking
        if ($booking['status'] !== 'confirmed' && $booking['status'] !== 'in_progress') {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Pembayaran hanya dapat dilakukan saat booking dikonfirmasi atau dalam proses pengerjaan.');
        }

        $paymentType = $this->request->getPost('payment_type') ?: 'QRIS';

        // Cari atau buat record payment baru
        $payment = $this->paymentModel->getByBooking($id);
        if ($payment) {
            $this->paymentModel->update($payment['id'], [
                'status' => 'paid',
                'payment_type' => $paymentType,
                'paid_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->paymentModel->insert([
                'booking_id' => $id,
                'order_id' => 'BKL-' . time(),
                'amount' => $booking['price'],
                'status' => 'paid',
                'payment_type' => $paymentType,
                'paid_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->to('/pelanggan/riwayat')->with('success', 'Pembayaran simulasi berhasil dikonfirmasi!');
    }

    public function review($id)
    {
        $booking = $this->bookingModel->getWithDetails($id);
        if (!$booking || $booking['user_id'] != session()->get('user_id')) {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Booking tidak ditemukan.');
        }

        if ($booking['status'] !== 'done') {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Ulasan hanya dapat diberikan untuk servis yang sudah selesai.');
        }

        // Cek apakah sudah pernah diulas
        $existingReview = $this->reviewModel->getByBooking($id);
        if ($existingReview) {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Anda sudah memberikan ulasan untuk booking ini.');
        }

        $data = [
            'title' => 'Berikan Ulasan',
            'booking' => $booking
        ];
        return view('pelanggan/v_review', $data);
    }

    public function reviewStore($id)
    {
        $booking = $this->bookingModel->getWithDetails($id);
        if (!$booking || $booking['user_id'] != session()->get('user_id')) {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Booking tidak ditemukan.');
        }

        // Cek apakah sudah pernah diulas
        $existingReview = $this->reviewModel->getByBooking($id);
        if ($existingReview) {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Anda sudah memberikan ulasan untuk booking ini.');
        }

        $rating = $this->request->getPost('rating') ?: 5;
        $comment = $this->request->getPost('comment') ?: '';

        $this->reviewModel->insert([
            'user_id' => $booking['user_id'],
            'service_id' => $booking['service_id'],
            'booking_id' => $id,
            'rating' => $rating,
            'comment' => $comment
        ]);

        return redirect()->to('/pelanggan/riwayat')->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
