<?php

namespace App\Controllers\Staff;

use App\Controllers\BaseController;
use App\Models\BookingModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $bookingModel = new BookingModel();
        $staffId = session()->get('user_id');

        $bookings = $bookingModel->select('bookings.*,users.name as user_name,users.phone as user_phone,services.name as service_name,schedules.available_date,schedules.slot_time')
            ->join('users','users.id = bookings.user_id')
            ->join('services','services.id = bookings.service_id')
            ->join('schedules','schedules.id = bookings.schedule_id')
            ->where('bookings.staff_id', $staffId)
            ->where('schedules.available_date', date('Y-m-d'))
            ->orderBy('schedules.slot_time','ASC')
            ->findAll();

        return view('staff/v_dashboard', ['title' => 'Dashboard Staff', 'bookings' => $bookings]);
    }

    public function jadwal()
    {
        $bookingModel = new BookingModel();
        $staffId = session()->get('user_id');

        $bookings = $bookingModel->select('bookings.*,users.name as user_name,services.name as service_name,schedules.available_date,schedules.slot_time')
            ->join('users','users.id = bookings.user_id')
            ->join('services','services.id = bookings.service_id')
            ->join('schedules','schedules.id = bookings.schedule_id')
            ->where('bookings.staff_id', $staffId)
            ->where('schedules.available_date >=', date('Y-m-d'))
            ->orderBy('schedules.available_date','ASC')
            ->orderBy('schedules.slot_time','ASC')
            ->findAll();

        return view('staff/v_jadwal', ['title' => 'Jadwal Saya', 'bookings' => $bookings]);
    }

    public function updateStatus($id)
    {
        $bookingModel = new BookingModel();
        $booking = $bookingModel->find($id);

        if (!$booking || $booking['staff_id'] != session()->get('user_id')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $status = $this->request->getPost('status');
        $allowed = ['in_progress', 'done'];

        if (!in_array($status, $allowed)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $bookingModel->update($id, ['status' => $status]);
        return redirect()->back()->with('success', 'Status berhasil diupdate.');
    }
}
