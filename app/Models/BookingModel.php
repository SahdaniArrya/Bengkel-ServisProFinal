<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table         = 'bookings';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['user_id', 'service_id', 'schedule_id', 'staff_id', 'notes', 'status'];
    protected $useTimestamps = true;

    /**
     * Ambil booking lengkap dengan join (untuk admin & display)
     */
    public function getWithDetails($id = null)
    {
        $builder = $this->select('
                bookings.*,
                users.name as user_name,
                users.email as user_email,
                users.phone as user_phone,
                services.name as service_name,
                services.price,
                schedules.available_date,
                schedules.slot_time,
                staff.name as staff_name,
                payments.status as payment_status,
                reviews.id as review_id
            ')
            ->join('users',     'users.id = bookings.user_id')
            ->join('services',  'services.id = bookings.service_id')
            ->join('schedules', 'schedules.id = bookings.schedule_id')
            ->join('staff',     'staff.id = bookings.staff_id', 'left')
            ->join('payments',  'payments.booking_id = bookings.id', 'left')
            ->join('reviews',   'reviews.booking_id = bookings.id', 'left');

        if ($id) {
            return $builder->where('bookings.id', $id)->first();
        }

        return $builder->orderBy('bookings.created_at', 'DESC')->findAll();
    }

    /**
     * Booking milik user tertentu
     */
    public function getByUser($userId)
    {
        return $this->select('
                bookings.*,
                services.name as service_name,
                services.price,
                schedules.available_date,
                schedules.slot_time,
                staff.name as staff_name,
                payments.status as payment_status,
                reviews.id as review_id
            ')
            ->join('services',  'services.id = bookings.service_id')
            ->join('schedules', 'schedules.id = bookings.schedule_id')
            ->join('staff',     'staff.id = bookings.staff_id', 'left')
            ->join('payments',  'payments.booking_id = bookings.id', 'left')
            ->join('reviews',   'reviews.booking_id = bookings.id', 'left')
            ->where('bookings.user_id', $userId)
            ->orderBy('bookings.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Statistik untuk dashboard admin
     */
    public function getStats()
    {
        return [
            'total'      => $this->countAll(),
            'pending'    => $this->where('status', 'pending')->countAllResults(),
            'confirmed'  => $this->where('status', 'confirmed')->countAllResults(),
            'done'       => $this->where('status', 'done')->countAllResults(),
            'cancelled'  => $this->where('status', 'cancelled')->countAllResults(),
        ];
    }
}
