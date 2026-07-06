<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * Filter untuk memvalidasi API Key pada setiap request ke endpoint /api/*
 * API Key dikirim melalui header: X-API-KEY
 */
class ApiKeyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Ambil API key dari header X-API-KEY
        $apiKey = $request->getHeaderLine('X-API-KEY');

        // Daftar API Key yang valid (bisa dipindah ke .env atau database)
        $validKeys = [
            'BENGKEL-SECRET-KEY-2024',
        ];

        if (empty($apiKey) || !in_array($apiKey, $validKeys)) {
            $response = service('response');
            $response->setStatusCode(401);
            $response->setContentType('application/json');
            $response->setBody(json_encode([
                'status'  => 401,
                'error'   => 'Unauthorized',
                'message' => 'API Key tidak valid atau tidak ditemukan. Sertakan header X-API-KEY yang benar.',
            ]));
            return $response;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tambahkan header CORS agar bisa diakses dari aplikasi mobile/frontend lain
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->setHeader('Access-Control-Allow-Headers', 'Content-Type, X-API-KEY, Authorization');
    }
}
