<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'         => 'Servis Rutin (Ganti Oli)',
                'description'  => 'Ganti oli mesin, cek kondisi busi, filter udara, dan rantai. Termasuk pemeriksaan umum kondisi motor.',
                'price'        => 75000,
                'duration_min' => 45,
                'photo'        => null,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Tune Up Mesin',
                'description'  => 'Setel karburator/injeksi, bersihkan throttle body, cek klep, setel idle, dan tes performa mesin.',
                'price'        => 150000,
                'duration_min' => 90,
                'photo'        => null,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Ganti Ban',
                'description'  => 'Penggantian ban depan atau belakang. Sudah termasuk balancing dan nitrogen. Harga belum termasuk ban.',
                'price'        => 50000,
                'duration_min' => 30,
                'photo'        => null,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Servis Rem',
                'description'  => 'Cek dan ganti kampas rem, bleeding minyak rem, setel rem depan dan belakang.',
                'price'        => 80000,
                'duration_min' => 60,
                'photo'        => null,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Cek Kelistrikan',
                'description'  => 'Diagnosa masalah kelistrikan: aki, kabel, lampu, klakson, sistem starter, dan sensor-sensor motor.',
                'price'        => 100000,
                'duration_min' => 60,
                'photo'        => null,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('services')->insertBatch($data);
    }
}
