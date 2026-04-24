<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Response;
use App\Services\MercadoPagoService;

final class WebhookController
{
    public function __construct(
        private readonly MercadoPagoService $mp = new MercadoPagoService()
    ) {}

    public function mercadopago(): never
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') Response::error('Method not allowed.', 405);

        $rawBody    = file_get_contents('php://input');
        $payload    = json_decode($rawBody, true) ?? [];
        $xSignature = $_SERVER['HTTP_X_SIGNATURE']   ?? '';
        $xRequestId = $_SERVER['HTTP_X_REQUEST_ID']  ?? '';

        try {
            $this->mp->handleWebhook($payload, $rawBody, $xSignature, $xRequestId);
            // Always 200 to MercadoPago so it doesn't retry
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['ok' => true]);
            exit;
        } catch (\Throwable $e) {
            error_log('Webhook error: ' . $e->getMessage());
            http_response_code(200); // still 200 to prevent retries
            echo json_encode(['ok' => false]);
            exit;
        }
    }
}
