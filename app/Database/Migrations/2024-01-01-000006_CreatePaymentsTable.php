<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'booking_id'   => ['type' => 'INT', 'unsigned' => true],
            'snap_token'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'order_id'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'amount'       => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'status'       => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'paid', 'failed', 'cancelled'],
                'default'    => 'pending',
            ],
            'payment_type' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'paid_at'      => ['type' => 'DATETIME', 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payments');
    }

    public function down()
    {
        $this->forge->dropTable('payments');
    }
}
