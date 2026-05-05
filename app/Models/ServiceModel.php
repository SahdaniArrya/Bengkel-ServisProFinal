<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table         = 'services';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['name', 'description', 'price', 'duration_min', 'photo', 'is_active'];
    protected $useTimestamps = true;

    /**
     * Ambil semua layanan yang aktif beserta rata-rata rating
     */
    public function getActiveWithRating()
    {
        return $this->select('services.*, COALESCE(AVG(reviews.rating), 0) as avg_rating, COUNT(reviews.id) as total_reviews')
                    ->join('reviews', 'reviews.service_id = services.id', 'left')
                    ->where('services.is_active', 1)
                    ->groupBy('services.id')
                    ->findAll();
    }
}
