<?php

namespace App\Libraries;

class Midtrans
{
    private $serverKey;
    private $isProduction;

    public function __construct()
    {
        // Replace with actual keys or take from env
        $this->serverKey = env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-YOUR_SERVER_KEY');
        $this->isProduction = env('MIDTRANS_IS_PRODUCTION', false);
    }

    public function getSnapToken($params)
    {
        $url = $this->isProduction 
            ? 'https://app.midtrans.com/snap/v1/transactions' 
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $ch = curl_init();
        $curl_options = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($this->serverKey . ':')
            ),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($params)
        );

        curl_setopt_array($ch, $curl_options);
        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result);
        return $response->token ?? null;
    }
}
