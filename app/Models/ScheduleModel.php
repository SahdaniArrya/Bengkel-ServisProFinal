<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table         = 'schedules';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['service_id', 'available_date', 'slot_time', 'capacity', 'booked_count'];
    protected $useTimestamps = true;

    /**
     * Ambil slot yang masih tersedia untuk service tertentu
     */
    public function getAvailableByService($serviceId, $date = null)
    {
        $builder = $this->where('service_id', $serviceId)
                        ->where('booked_count <', $this->db->protectIdentifiers('capacity'), false)
                        ->where('available_date >=', date('Y-m-d'));

        if ($date) {
            $builder->where('available_date', $date);
        }

        return $builder->orderBy('available_date', 'ASC')->orderBy('slot_time', 'ASC')->findAll();
    }
}
