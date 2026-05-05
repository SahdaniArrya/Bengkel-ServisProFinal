<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        $schedules = [];
        $slots = ['08:00:00', '09:00:00', '10:00:00', '11:00:00', '13:00:00', '14:00:00', '15:00:00'];

        // Buat jadwal untuk 14 hari ke depan, semua service
        for ($day = 0; $day <= 13; $day++) {
            $date = date('Y-m-d', strtotime("+$day days"));
            // Skip hari Minggu
            if (date('N', strtotime($date)) == 7) continue;

            for ($serviceId = 1; $serviceId <= 5; $serviceId++) {
                foreach ($slots as $slot) {
                    $schedules[] = [
                        'service_id'     => $serviceId,
                        'available_date' => $date,
                        'slot_time'      => $slot,
                        'capacity'       => 3,
                        'booked_count'   => 0,
                        'created_at'     => date('Y-m-d H:i:s'),
                        'updated_at'     => date('Y-m-d H:i:s'),
                    ];
                }
            }
        }
        $this->db->table('schedules')->insertBatch($schedules);
    }
}
