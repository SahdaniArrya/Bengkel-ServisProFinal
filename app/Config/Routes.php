<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index', ['filter' => 'auth']);

// ===================== ROUTES LAMA =====================
$routes->get('login', 'LoginController::index');
$routes->post('login', 'LoginController::auth');
$routes->get('logout', 'LoginController::logout');

$routes->get('produk', 'ProdukController::index', ['filter' => 'auth']);
$routes->get('keranjang', 'TransaksiController::index', ['filter' => 'auth']);

// ===================== ROUTES BENGKEL BARU =====================

// Auth
$routes->get('auth/login',    'AuthController::login');
$routes->post('auth/login',   'AuthController::loginProcess');
$routes->get('auth/register', 'AuthController::register');
$routes->post('auth/register','AuthController::registerProcess');
$routes->get('admin/logout',   'AuthController::logout');
$routes->get('staff/logout',   'AuthController::logout');
$routes->get('pelanggan/logout',   'AuthController::logout');

// Dashboard redirect
$routes->get('dashboard', 'AuthController::dashboard', ['filter' => 'auth']);

// Profile
$routes->group('profile', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ProfileController::index');
    $routes->get('settings', 'ProfileController::settings');
    $routes->post('update', 'ProfileController::update');
    $routes->get('help', 'ProfileController::help');
});

// Admin
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('dashboard',                   'Admin\DashboardController::index');
    $routes->get('services',                    'Admin\ServiceController::index');
    $routes->get('services/create',             'Admin\ServiceController::create');
    $routes->post('services/store',             'Admin\ServiceController::store');
    $routes->get('services/edit/(:num)',        'Admin\ServiceController::edit/$1');
    $routes->post('services/update/(:num)',     'Admin\ServiceController::update/$1');
    $routes->get('services/delete/(:num)',      'Admin\ServiceController::delete/$1');
    $routes->get('bookings',                    'Admin\BookingController::index');
    $routes->get('bookings/(:num)',             'Admin\BookingController::show/$1');
    $routes->post('bookings/confirm/(:num)',    'Admin\BookingController::confirm/$1');
    $routes->get('bookings/reject/(:num)',      'Admin\BookingController::reject/$1');
    $routes->get('staff',                       'Admin\StaffController::index');
    $routes->get('staff/create',                'Admin\StaffController::create');
    $routes->post('staff/store',                'Admin\StaffController::store');
    $routes->get('staff/edit/(:num)',           'Admin\StaffController::edit/$1');
    $routes->post('staff/update/(:num)',        'Admin\StaffController::update/$1');
    $routes->get('staff/delete/(:num)',         'Admin\StaffController::delete/$1');
    $routes->get('staff/toggle/(:num)',         'Admin\StaffController::toggle/$1');
    
    $routes->get('users',                       'Admin\UserController::index');
    $routes->get('users/(:num)',                'Admin\UserController::show/$1');
    $routes->get('users/delete/(:num)',         'Admin\UserController::delete/$1');
    $routes->get('users/toggle/(:num)',         'Admin\UserController::toggle/$1');
});

// Staff
$routes->group('staff', ['filter' => 'auth:staff'], function($routes) {
    $routes->get('dashboard',               'Staff\DashboardController::index');
    $routes->get('jadwal',                  'Staff\DashboardController::jadwal');
    $routes->post('update-status/(:num)',   'Staff\DashboardController::updateStatus/$1');
});

// Pelanggan
$routes->group('pelanggan', ['filter' => 'auth:pelanggan'], function($routes) {
    $routes->get('booking',                     'Pelanggan\BookingController::index');
    $routes->get('booking/jadwal/(:num)',       'Pelanggan\BookingController::pilihJadwal/$1');
    $routes->get('booking/konfirmasi',          'Pelanggan\BookingController::konfirmasi');
    $routes->post('booking/store',              'Pelanggan\BookingController::store');
    $routes->get('booking/cancel/(:num)',       'Pelanggan\BookingController::cancel/$1');
    $routes->get('riwayat',                     'Pelanggan\BookingController::riwayat');
    
    // Pembayaran & Ulasan (UAS)
    $routes->get('booking/payment/(:num)',      'Pelanggan\BookingController::payment/$1');
    $routes->post('booking/pay-process/(:num)', 'Pelanggan\BookingController::payProcess/$1');
    $routes->get('booking/review/(:num)',       'Pelanggan\BookingController::review/$1');
    $routes->post('booking/review-store/(:num)','Pelanggan\BookingController::reviewStore/$1');
});
