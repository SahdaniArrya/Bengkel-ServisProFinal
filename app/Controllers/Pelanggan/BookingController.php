<?php

namespace App\Controllers\Pelanggan;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\ServiceModel;
use App\Models\ScheduleModel;
use App\Models\PaymentModel;
use App\Models\ReviewModel;

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
        $this->bookingModel->insert(['user_id'=>$userId,'service_id'=>$serviceId,'schedule_id'=>$scheduleId,'notes'=>$this->request->getPost('notes'),'status'=>'pending']);
        $this->scheduleModel->set('booked_count','booked_count + 1',false)->update($scheduleId);
        return redirect()->to('/pelanggan/riwayat')->with('success','Booking berhasil! Menunggu konfirmasi bengkel.');
    }

    public function riwayat()
    {
        return view('pelanggan/v_riwayat', ['title'=>'Riwayat Booking','bookings'=>$this->bookingModel->getByUser(session()->get('user_id'))]);
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

        $data = [
            'title' => 'Simulasi Pembayaran (UAS)',
            'booking' => $booking
        ];
        return view('pelanggan/v_payment', $data);
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
