<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Budi Santoso',  'phone' => '081111111111', 'specialization' => 'Mesin & Tune Up',       'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Reza Pratama',  'phone' => '082222222222', 'specialization' => 'Kelistrikan Motor',      'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Joko Susilo',   'phone' => '083333333333', 'specialization' => 'Servis Rem & Kampas',    'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('staff')->insertBatch($data);
    }
}
