<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'     => ['type' => 'INT', 'unsigned' => true],
            'service_id'  => ['type' => 'INT', 'unsigned' => true],
            'schedule_id' => ['type' => 'INT', 'unsigned' => true],
            'staff_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'notes'       => ['type' => 'TEXT', 'null' => true],
            'status'      => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'in_progress', 'done', 'cancelled'],
                'default'    => 'pending',
            ],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id',     'users',     'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('service_id',  'services',  'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('schedule_id', 'schedules', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('staff_id',    'staff',     'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('bookings');
    }

    public function down()
    {
        $this->forge->dropTable('bookings');
    }
}
