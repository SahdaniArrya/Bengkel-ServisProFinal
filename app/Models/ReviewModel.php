<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table         = 'reviews';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['user_id', 'service_id', 'booking_id', 'rating', 'comment'];
    protected $useTimestamps = true;

    public function getByBooking($bookingId)
    {
        return $this->where('booking_id', $bookingId)->first();
    }
}
