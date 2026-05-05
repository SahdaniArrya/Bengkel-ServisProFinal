<?php

namespace App\Controllers\Pelanggan;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\ReviewModel;

class ReviewController extends BaseController
{
    protected $bookingModel, $reviewModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->reviewModel = new ReviewModel();
    }

    public function index($bookingId)
    {
        $userId = session()->get('user_id');
        $booking = $this->bookingModel->getWithDetails($bookingId);

        if (!$booking || $booking['user_id'] != $userId) {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Booking tidak valid.');
        }

        if ($booking['status'] !== 'done') {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Anda hanya dapat memberikan ulasan setelah layanan selesai.');
        }

        $existingReview = $this->reviewModel->getByBookingId($bookingId);
        if ($existingReview) {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Anda sudah memberikan ulasan untuk layanan ini.');
        }

        return view('pelanggan/v_review', [
            'title'   => 'Beri Ulasan Layanan',
            'booking' => $booking
        ]);
    }

    public function store($bookingId)
    {
        $userId = session()->get('user_id');
        $booking = $this->bookingModel->getWithDetails($bookingId);

        if (!$booking || $booking['user_id'] != $userId) {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Booking tidak valid.');
        }

        if ($booking['status'] !== 'done') {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Anda hanya dapat memberikan ulasan setelah layanan selesai.');
        }

        $existingReview = $this->reviewModel->getByBookingId($bookingId);
        if ($existingReview) {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Anda sudah memberikan ulasan untuk layanan ini.');
        }

        $rating = (int) $this->request->getPost('rating');
        $comment = $this->request->getPost('comment');

        if ($rating < 1 || $rating > 5) {
            return redirect()->back()->with('error', 'Rating harus antara 1 dan 5.');
        }

        $this->reviewModel->insert([
            'user_id'    => $userId,
            'service_id' => $booking['service_id'],
            'booking_id' => $bookingId,
            'rating'     => $rating,
            'comment'    => $comment
        ]);

        return redirect()->to('/pelanggan/riwayat')->with('success', 'Terima kasih! Ulasan Anda berhasil disimpan.');
    }
}
