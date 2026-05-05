<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('UserSeeder');
        $this->call('StaffSeeder');
        $this->call('ServiceSeeder');
        $this->call('ScheduleSeeder');
        $this->call('BookingSeeder');
    }
}
