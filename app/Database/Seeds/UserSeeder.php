<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'       => 'Admin Bengkel',
                'email'      => 'admin@bengkel.com',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'phone'      => '081234567890',
                'role'       => 'admin',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Budi Mekanik',
                'email'      => 'staff@bengkel.com',
                'password'   => password_hash('staff123', PASSWORD_DEFAULT),
                'phone'      => '081234567891',
                'role'       => 'staff',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Andi Pelanggan',
                'email'      => 'pelanggan@gmail.com',
                'password'   => password_hash('user123', PASSWORD_DEFAULT),
                'phone'      => '082345678901',
                'role'       => 'pelanggan',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Siti Rahayu',
                'email'      => 'siti@gmail.com',
                'password'   => password_hash('user123', PASSWORD_DEFAULT),
                'phone'      => '083456789012',
                'role'       => 'pelanggan',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
