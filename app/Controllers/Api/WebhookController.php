<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\PaymentModel;
use App\Models\BookingModel;
use App\Libraries\NotificationService;
use CodeIgniter\API\ResponseTrait;

class WebhookController extends BaseController
{
    use ResponseTrait;

    public function midtransCallback()
    {
        // Mendapatkan isi JSON dari Midtrans
        $json = $this->request->getJSON();
        
        if (!$json) {
            return $this->fail('Invalid payload', 400);
        }

        // Ambil server key
        $serverKey = env('MIDTRANS_SERVER_KEY', '');
        
        // Verifikasi signature key
        // rumusnya: hash("sha512", order_id + status_code + gross_amount + server_key)
        $signatureKey = hash("sha512", $json->order_id . $json->status_code . $json->gross_amount . $serverKey);
        
        if ($signatureKey !== $json->signature_key) {
            return $this->failUnauthorized('Invalid signature');
        }

        $paymentModel = new PaymentModel();
        $bookingModel = new BookingModel();
        $notifService = new NotificationService();

        $payment = $paymentModel->where('order_id', $json->order_id)->first();
        if (!$payment) {
            return $this->failNotFound('Order ID tidak ditemukan');
        }

        $transactionStatus = $json->transaction_status;
        $type = $json->payment_type;
        
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            // Update status pembayaran
            $paymentModel->update($payment['id'], [
                'status' => 'paid',
                'payment_type' => $type,
                'paid_at' => date('Y-m-d H:i:s')
            ]);
            
            // Ambil data detail booking untuk dikirim di email
            $booking = $bookingModel->getWithDetails($payment['booking_id']);
            
            if ($booking && !empty($booking['user_email'])) {
                $notifService->sendPaymentSuccess(
                    $booking['user_email'], 
                    $booking['user_name'], 
                    $booking['service_name'], 
                    $payment['amount'], 
                    $json->order_id
                );
            }

            return $this->respond(['message' => 'Payment status updated to paid'], 200);

        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $paymentModel->update($payment['id'], [
                'status' => 'failed'
            ]);
            return $this->respond(['message' => 'Payment status updated to failed'], 200);
            
        } else if ($transactionStatus == 'pending') {
            $paymentModel->update($payment['id'], [
                'status' => 'pending'
            ]);
            return $this->respond(['message' => 'Payment status updated to pending'], 200);
        }

        return $this->respond(['message' => 'Notification handled'], 200);
    }
}
