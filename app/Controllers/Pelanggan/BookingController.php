<?php

namespace App\Controllers\Pelanggan;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\ServiceModel;
use App\Models\ScheduleModel;

class BookingController extends BaseController
{
    protected $bookingModel, $serviceModel, $scheduleModel;
    public function __construct()
    {
        $this->bookingModel  = new BookingModel();
        $this->serviceModel  = new ServiceModel();
        $this->scheduleModel = new ScheduleModel();
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
}
