<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id'     => 3,
                'service_id'  => 1,
                'schedule_id' => 1,
                'staff_id'    => 1,
                'notes'       => 'Motor Honda Beat 2020, plat H 1234 AB',
                'status'      => 'done',
                'created_at'  => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at'  => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
            [
                'user_id'     => 3,
                'service_id'  => 2,
                'schedule_id' => 8,
                'staff_id'    => 2,
                'notes'       => 'Motor terasa boros, perlu tune up',
                'status'      => 'confirmed',
                'created_at'  => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at'  => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'user_id'     => 4,
                'service_id'  => 3,
                'schedule_id' => 15,
                'staff_id'    => null,
                'notes'       => 'Ban bocor, minta ganti baru',
                'status'      => 'pending',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('bookings')->insertBatch($data);
    }
}
