<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
    protected $table         = 'staff';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['name', 'phone', 'specialization', 'is_active'];
    protected $useTimestamps = true;
}
