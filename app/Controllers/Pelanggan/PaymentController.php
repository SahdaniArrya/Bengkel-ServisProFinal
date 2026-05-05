<?php

namespace App\Controllers\Pelanggan;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\PaymentModel;
use App\Libraries\Midtrans;

class PaymentController extends BaseController
{
    protected $bookingModel, $paymentModel, $midtrans;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->paymentModel = new PaymentModel();
        $this->midtrans = new Midtrans();
    }

    public function pay($bookingId)
    {
        $userId = session()->get('user_id');
        $booking = $this->bookingModel->getWithDetails($bookingId);

        if (!$booking || $booking['user_id'] != $userId) {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Booking tidak valid.');
        }

        // Cek apakah sudah ada payment pending atau paid
        $payment = $this->paymentModel->getByBookingId($bookingId);
        if ($payment && $payment['status'] === 'paid') {
            return redirect()->to('/pelanggan/riwayat')->with('error', 'Pembayaran sudah lunas.');
        }

        $orderId = 'TRX-' . time() . '-' . $bookingId;
        $amount = (int) $booking['price'];

        if (!$payment) {
            $paymentId = $this->paymentModel->insert([
                'booking_id' => $bookingId,
                'order_id'   => $orderId,
                'amount'     => $amount,
                'status'     => 'pending',
            ]);
            $payment = $this->paymentModel->find($paymentId);
        } else {
            $orderId = $payment['order_id'];
        }

        // Generate Snap Token
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $booking['user_name'],
                'phone'      => $booking['user_phone'],
            ]
        ];

        $snapToken = $payment['snap_token'];
        if (!$snapToken) {
            $snapToken = $this->midtrans->getSnapToken($params);
            if ($snapToken) {
                $this->paymentModel->update($payment['id'], ['snap_token' => $snapToken]);
            } else {
                return redirect()->to('/pelanggan/riwayat')->with('error', 'Gagal membuat token pembayaran. Cek konfigurasi Midtrans.');
            }
        }

        return view('pelanggan/v_payment', [
            'title'     => 'Pembayaran Booking',
            'booking'   => $booking,
            'snapToken' => $snapToken,
            'isProduction' => env('MIDTRANS_IS_PRODUCTION', false),
            'clientKey' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-YOUR_CLIENT_KEY'),
        ]);
    }

    public function finish()
    {
        // Parameter kembalian dari Midtrans: order_id, status_code, transaction_status
        $orderId = $this->request->getGet('order_id');
        $transactionStatus = $this->request->getGet('transaction_status');

        $payment = $this->paymentModel->getByOrderId($orderId);
        if ($payment) {
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                $this->paymentModel->update($payment['id'], ['status' => 'paid', 'paid_at' => date('Y-m-d H:i:s')]);
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                $this->paymentModel->update($payment['id'], ['status' => 'failed']);
            }
        }

        return redirect()->to('/pelanggan/riwayat')->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
