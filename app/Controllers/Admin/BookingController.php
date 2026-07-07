<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\StaffModel;
use App\Models\PaymentModel;

class BookingController extends BaseController
{
    protected $bookingModel;
    protected $staffModel;
    protected $paymentModel;
    public function __construct() { 
        $this->bookingModel = new BookingModel(); 
        $this->staffModel = new StaffModel(); 
        $this->paymentModel = new PaymentModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $date   = $this->request->getGet('date');
        $builder = $this->bookingModel->select('bookings.*,users.name as user_name,users.phone as user_phone,services.name as service_name,services.price,schedules.available_date,schedules.slot_time,staff.name as staff_name,payments.status as payment_status,payments.amount as payment_amount')
            ->join('users','users.id = bookings.user_id')
            ->join('services','services.id = bookings.service_id')
            ->join('schedules','schedules.id = bookings.schedule_id')
            ->join('staff','staff.id = bookings.staff_id','left')
            ->join('payments','payments.booking_id = bookings.id','left')
            ->orderBy('bookings.created_at','DESC');
        if ($status) $builder->where('bookings.status', $status);
        if ($date)   $builder->where('schedules.available_date', $date);
        return view('admin/bookings/v_index', ['title'=>'Kelola Booking','bookings'=>$builder->paginate(15),'pager'=>$this->bookingModel->pager,'status'=>$status,'date'=>$date]);
    }

    public function show($id)
    {
        $booking = $this->bookingModel->getWithDetails($id);
        if (!$booking) return redirect()->to('/admin/bookings')->with('error', 'Booking tidak ditemukan.');
        $payment = $this->paymentModel->getByBooking($id);
        return view('admin/bookings/v_show', ['title'=>'Detail Booking','booking'=>$booking,'staffs'=>$this->staffModel->where('is_active',1)->findAll(),'payment'=>$payment]);
    }

    public function confirm($id)
    {
        $booking = $this->bookingModel->find($id);
        if (!$booking || $booking['status'] !== 'pending') return redirect()->back()->with('error', 'Booking tidak dapat dikonfirmasi.');
        $this->bookingModel->update($id, ['status'=>'confirmed','staff_id'=>$this->request->getPost('staff_id') ?: null]);
        return redirect()->to('/admin/bookings/'.$id)->with('success', 'Booking berhasil dikonfirmasi.');
    }

    public function reject($id)
    {
        $booking = $this->bookingModel->find($id);
        if (!$booking) return redirect()->back()->with('error', 'Booking tidak ditemukan.');
        $db = \Config\Database::connect();
        $db->table('schedules')->where('id',$booking['schedule_id'])->set('booked_count','booked_count - 1',false)->update();
        $this->bookingModel->update($id, ['status'=>'cancelled']);
        return redirect()->to('/admin/bookings')->with('success', 'Booking berhasil ditolak.');
    }
}
