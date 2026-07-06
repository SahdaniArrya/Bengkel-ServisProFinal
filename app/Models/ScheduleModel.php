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
        if ($date) {
            // Auto generate slots if they don't exist for the requested date
            // Skip Sunday (7)
            if (date('N', strtotime($date)) != 7) {
                $existing = $this->where('available_date', $date)->first();
                if (!$existing) {
                    $this->generateDailySlots($date);
                }
            }
        }

        $builder = $this->where('service_id', $serviceId)
                        ->where('booked_count <', $this->db->protectIdentifiers('capacity'), false)
                        ->where('available_date >=', date('Y-m-d'));

        if ($date) {
            $builder->where('available_date', $date);
            // Jika tanggal yang dipilih adalah hari ini, jangan tampilkan slot yang jamnya sudah lewat
            if ($date === date('Y-m-d')) {
                $builder->where('slot_time >', date('H:i:s'));
            }
        }

        return $builder->orderBy('available_date', 'ASC')->orderBy('slot_time', 'ASC')->findAll();
    }

    /**
     * Auto generate schedule for a specific date for all active services
     */
    private function generateDailySlots($date)
    {
        $db = \Config\Database::connect();
        $services = $db->table('services')->where('is_active', 1)->get()->getResultArray();
        
        $slots = ['08:00:00', '09:00:00', '10:00:00', '11:00:00', '13:00:00', '14:00:00', '15:00:00'];
        $schedules = [];

        foreach ($services as $service) {
            foreach ($slots as $slot) {
                $schedules[] = [
                    'service_id'     => $service['id'],
                    'available_date' => $date,
                    'slot_time'      => $slot,
                    'capacity'       => 3,
                    'booked_count'   => 0,
                    'created_at'     => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s'),
                ];
            }
        }

        if (!empty($schedules)) {
            $this->insertBatch($schedules);
        }
    }
}
