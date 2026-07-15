<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;

class NotificationService
{
    protected Email $email;

    public function __construct()
    {
        $this->email = \Config\Services::email();
        
        // Setup configuration from .env
        $config['protocol']    = 'smtp';
        $config['SMTPHost']    = env('SMTP_HOST', 'smtp.gmail.com');
        $config['SMTPUser']    = env('SMTP_USER', '');
        $config['SMTPPass']    = env('SMTP_PASS', '');
        $config['SMTPPort']    = (int) env('SMTP_PORT', 465);
        $config['SMTPCrypto']  = env('SMTP_CRYPTO', 'ssl');
        $config['SMTPTimeout'] = 30; // naik dari 5 agar tidak timeout di Gmail
        $config['mailType']    = 'html';
        $config['charset']     = 'utf-8';
        $config['wordWrap']    = true;
        $config['CRLF']        = "\r\n";
        $config['newline']     = "\r\n";
        $config['validate']    = false;

        $this->email->initialize($config);
    }

    /**
     * Send Booking Confirmation Email
     */
    public function sendBookingConfirmation($toEmail, $userName, $serviceName, $date, $time)
    {
        // Don't send if SMTP not configured yet
        if (empty(env('SMTP_PASS'))) {
            log_message('warning', '[NotificationService] SMTP_PASS kosong, email booking tidak dikirim.');
            return false;
        }

        $this->email->clear();
        $this->email->setFrom(env('SMTP_USER', 'bengkel@example.com'), 'Bengkel ServisPro');
        $this->email->setTo($toEmail);
        $this->email->setSubject('Konfirmasi Booking Servis - Bengkel ServisPro');

        $message = "
        <h2>Halo, {$userName}!</h2>
        <p>Terima kasih telah melakukan booking di Bengkel ServisPro.</p>
        <p>Berikut adalah detail booking Anda:</p>
        <ul>
            <li><strong>Layanan:</strong> {$serviceName}</li>
            <li><strong>Tanggal:</strong> {$date}</li>
            <li><strong>Waktu:</strong> {$time} WIB</li>
        </ul>
        <p>Kami akan segera memproses booking Anda. Tunggu konfirmasi dari admin bengkel.</p>
        <p>Terima kasih,<br>Tim Bengkel ServisPro</p>";

        $this->email->setMessage($message);

        $result = $this->email->send(false);
        if (!$result) {
            $errorMsg = $this->email->printDebugger(['headers']);
            log_message('error', '[NotificationService] Gagal kirim email booking ke ' . $toEmail . '. Debug: ' . $errorMsg);
            return $errorMsg;
        } else {
            log_message('info', '[NotificationService] Email booking confirmation berhasil dikirim ke ' . $toEmail);
            return true;
        }
    }

    /**
     * Send Payment Success Email
     */
    public function sendPaymentSuccess($toEmail, $userName, $serviceName, $amount, $orderId)
    {
        // Don't send if SMTP not configured yet
        if (empty(env('SMTP_PASS'))) {
            log_message('warning', '[NotificationService] SMTP_PASS kosong, email payment tidak dikirim.');
            return false;
        }

        $this->email->clear();
        $this->email->setFrom(env('SMTP_USER', 'bengkel@example.com'), 'Bengkel ServisPro');
        $this->email->setTo($toEmail);
        $this->email->setSubject('Pembayaran Berhasil - Bengkel ServisPro');

        $message = "
        <h2>Halo, {$userName}!</h2>
        <p>Pembayaran Anda telah berhasil kami terima.</p>
        <p>Berikut adalah detail pembayaran Anda:</p>
        <ul>
            <li><strong>Order ID:</strong> {$orderId}</li>
            <li><strong>Layanan:</strong> {$serviceName}</li>
            <li><strong>Total Bayar:</strong> Rp " . number_format($amount, 0, ',', '.') . "</li>
        </ul>
        <p>Mekanik kami sedang menunggu kedatangan Anda sesuai jadwal.</p>
        <p>Terima kasih,<br>Tim Bengkel ServisPro</p>";

        $this->email->setMessage($message);

        $result = $this->email->send(false);
        if (!$result) {
            $errorMsg = $this->email->printDebugger(['headers']);
            log_message('error', '[NotificationService] Gagal kirim email payment ke ' . $toEmail . '. Debug: ' . $errorMsg);
            return $errorMsg;
        } else {
            log_message('info', '[NotificationService] Email payment success berhasil dikirim ke ' . $toEmail);
            return true;
        }
    }
}
