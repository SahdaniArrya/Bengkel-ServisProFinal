<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table         = 'payments';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['booking_id', 'snap_token', 'order_id', 'amount', 'status', 'payment_type', 'paid_at'];
    protected $useTimestamps = true;

    public function getByBooking($bookingId)
    {
        return $this->where('booking_id', $bookingId)->first();
    }
}
