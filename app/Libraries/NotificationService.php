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
        $config['protocol']   = 'smtp';
        $config['SMTPHost']   = env('SMTP_HOST', 'smtp.gmail.com');
        $config['SMTPUser']   = env('SMTP_USER', '');
        $config['SMTPPass']   = env('SMTP_PASS', '');
        $config['SMTPPort']   = env('SMTP_PORT', 465);
        $config['SMTPCrypto'] = env('SMTP_CRYPTO', 'ssl');
        $config['mailType']   = 'html';
        $config['charset']    = 'utf-8';
        $config['wordWrap']   = true;
        $config['CRLF']       = "\r\n";
        $config['newline']    = "\r\n";

        $this->email->initialize($config);
    }

    /**
     * Send Booking Confirmation Email
     */
    public function sendBookingConfirmation($toEmail, $userName, $serviceName, $date, $time)
    {
        // Don't send if SMTP not configured yet
        if (empty(env('SMTP_PASS'))) return false;

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
        <p>Silakan segera lakukan pembayaran melalui dashboard pelanggan Anda.</p>
        <p>Terima kasih,<br>Tim Bengkel ServisPro</p>";

        $this->email->setMessage($message);

        return $this->email->send();
    }

    /**
     * Send Payment Success Email
     */
    public function sendPaymentSuccess($toEmail, $userName, $serviceName, $amount, $orderId)
    {
        // Don't send if SMTP not configured yet
        if (empty(env('SMTP_PASS'))) return false;

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

        return $this->email->send();
    }
}
