<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Libraries\WeatherService;

class DashboardController extends BaseController
{
    public function index()
    {
        $bookingModel   = new BookingModel();
        $db             = \Config\Database::connect();
        $weatherService = new WeatherService();

        return view('admin/v_dashboard', [
            'title'      => 'Dashboard Admin',
            'stats'      => $bookingModel->getStats(),
            'pendapatan' => $db->table('payments')->selectSum('amount')->where('status', 'paid')->get()->getRow()->amount ?? 0,
            'recent'     => $bookingModel->getWithDetails(),
            'weather'    => $weatherService->getWeather(), // Data cuaca BMKG (Milestone 5)
        ]);
    }
}

