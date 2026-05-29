<?php
declare(strict_types=1);

namespace App\Services;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use App\Repositories\OrdenRepository;
use App\Repositories\PagoRepository;
use App\Repositories\ClienteRepository;
use App\Repositories\PaqueteRepository;
use App\Repositories\OrdenLogRepository;

final class MercadoPagoService
{
    private bool $booted = false;

    public function __construct(
        private readonly OrdenRepository    $ordenes  = new OrdenRepository(),
        private readonly PagoRepository     $pagos    = new PagoRepository(),
        private readonly ClienteRepository  $clientes = new ClienteRepository(),
        private readonly PaqueteRepository  $paquetes = new PaqueteRepository(),
        private readonly OrdenLogRepository $logs     = new OrdenLogRepository(),
        private readonly EmailService       $email    = new EmailService(),
    ) {}

    private function boot(): void
    {
        if (!$this->booted) {
            MercadoPagoConfig::setAccessToken($_ENV['MP_ACCESS_TOKEN']);
            $this->booted = true;
        }
    }

    /**
     * Create a MercadoPago preference for an order.
     * Returns ['init_point' => url, 'preference_id' => string]
     */
    public function createPreference(int $ordenId, int $clienteId): array
    {
        $this->boot();

        $orden   = $this->ordenes->findById($ordenId);
        if (!$orden || (int)$orden['cliente_id'] !== $clienteId) {
            throw new \RuntimeException('Orden no encontrada.');
        }
        if (!in_array($orden['estado'], ['pendiente_pago'], true)) {
            throw new \RuntimeException('La orden debe estar confirmada antes de pagar.');
        }

        $paquete = $this->paquetes->findById((int)$orden['paquete_id']);
        $cliente = $this->clientes->findById($clienteId);

        $client   = new PreferenceClient();
        $payload = [
            'items' => [[
                'id'          => "orden_{$ordenId}",
                'title'       => "{$paquete['emoji']} {$paquete['nombre']} — TaquerosWeb",
                'description' => mb_substr($orden['descripcion'], 0, 255),
                'quantity'    => 1,
                'currency_id' => 'MXN',
                'unit_price'  => (float) $paquete['precio'],
            ]],

            'payer' => [
                'name'    => $cliente['nombre'],
                'surname' => $cliente['apellidos'],
                'email'   => $cliente['email'],
                'phone'   => ['number' => $cliente['telefono']],
            ],

            'payment_methods' => [
                'excluded_payment_types' => [
                    ['id' => 'ticket'],
                    ['id' => 'atm'],
                    ['id' => 'bank_transfer']
                ],
                'installments' => 12,
            ],

            'back_urls' => [
                'success' => APP_URL . '/dashboard?pago=aprobado&orden=' . $ordenId,
                'failure' => APP_URL . '/dashboard?pago=error&orden=' . $ordenId,
                'pending' => APP_URL . '/dashboard?pago=pendiente&orden=' . $ordenId,
            ],

            'auto_return'          => 'approved',
            'external_reference'   => (string) $ordenId,
            'notification_url'     => APP_URL . '/api/webhook-mp',
            'statement_descriptor' => 'TAQUEROSWEB',
            'expires'              => true,
            'expiration_date_to'   => date('Y-m-d\TH:i:s.000\-05:00', strtotime('+2 days')),
        ];

        try {
            $preference = $client->create($payload);
            $prefId     = $preference->id;

            $this->ordenes->attachMpPreference($ordenId, $prefId);
            $this->logs->log($ordenId, 'pago_iniciado', "Preference MP creada: {$prefId}", $clienteId);

            return [
                'init_point'    => $preference->init_point,
                'preference_id' => $prefId,
            ];
        } catch (MPApiException $e) {
            throw new \RuntimeException('Error al crear el pago: ' . $e->getMessage());
        }
    }

    /**
     * Process MercadoPago IPN/webhook.
     * Returns true if processed, false if skipped (duplicate/irrelevant).
     */
    public function handleWebhook(array $payload, string $rawBody, string $xSignature, string $xRequestId): bool
    {
        // ── Signature validation ────────────────────────────────────────────
        if (!empty($_ENV['MP_WEBHOOK_SECRET'])) {
            $ts      = '';
            $v1      = '';
            foreach (explode(',', $xSignature) as $part) {
                [$k, $v] = explode('=', trim($part), 2);
                if ($k === 'ts') $ts = $v;
                if ($k === 'v1') $v1 = $v;
            }
            $manifest = "id:{$payload['data']['id']};request-id:{$xRequestId};ts:{$ts};";
            $expected = hash_hmac('sha256', $manifest, $_ENV['MP_WEBHOOK_SECRET']);
            if (!hash_equals($expected, $v1)) {
                error_log('MP Webhook: invalid signature');
                return false;
            }
        }

        if (($payload['type'] ?? '') !== 'payment') {
            return false;
        }

        $mpPaymentId = (string)($payload['data']['id'] ?? '');
        if (!$mpPaymentId) return false;

        // Deduplicate
        if ($this->pagos->findByMpPaymentId($mpPaymentId)) {
            return true; // already processed
        }

        // Fetch payment details from MP
        $this->boot();
        $paymentClient = new PaymentClient();
        try {
            $payment = $paymentClient->get((int) $mpPaymentId);
        } catch (MPApiException $e) {
            error_log('MP Webhook: failed to fetch payment ' . $e->getMessage());
            return false;
        }

        $ordenId   = (int)($payment->external_reference ?? 0);
        $mpStatus  = $payment->status;
        $monto     = (float)($payment->transaction_amount ?? 0);
        $metodo    = $payment->payment_method_id ?? 'mercadopago';

        $orden = $this->ordenes->findById($ordenId);
        if (!$orden) return false;

        // Record payment
        $this->pagos->create([
            'orden_id'       => $ordenId,
            'mp_payment_id'  => $mpPaymentId,
            'mp_status'      => $mpStatus,
            'monto'          => $monto,
            'metodo_pago'    => $metodo,
        ]);

        if ($mpStatus === 'approved') {
            $this->ordenes->updateEstado($ordenId, 'pagado');
            $this->logs->log($ordenId, 'pago_aprobado', "MP pago {$mpPaymentId} aprobado. Monto: \${$monto} MXN");

            $cliente = $this->clientes->findById((int)$orden['cliente_id']);
            $paquete = $this->paquetes->findById((int)$orden['paquete_id']);
            $this->email->sendPaymentApproved($cliente['email'], $cliente['nombre'], $orden, $paquete);
        } elseif (in_array($mpStatus, ['rejected', 'cancelled'], true)) {
            $this->logs->log($ordenId, 'pago_rechazado', "MP pago {$mpPaymentId} rechazado/cancelado.");
        }

        return true;
    }
}
