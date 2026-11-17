<?php

namespace App\Services;

use App\Models\Registration;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = (string) config('services.midtrans.server_key', '');
        $this->clientKey = (string) config('services.midtrans.client_key', '');
        $this->isProduction = (bool) config('services.midtrans.is_production', false);

        Config::$serverKey = $this->serverKey;
        Config::$clientKey = $this->clientKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = (bool) config('services.midtrans.is_sanitized', true);
        Config::$is3ds = (bool) config('services.midtrans.is_3ds', true);
    }

    /**
     * Create Midtrans Snap token for a registration.
     * Price strictly taken from workshop in database, NEVER from request!
     */
    public function createSnapToken(Registration $registration): string
    {
        $registration->loadMissing('workshop', 'user');

        $price = (int) $registration->workshop->price;

        $params = [
            'transaction_details' => [
                'order_id' => $registration->order_id,
                'gross_amount' => $price,
            ],
            'item_details' => [
                [
                    'id' => (string) $registration->workshop->id,
                    'price' => $price,
                    'quantity' => 1,
                    'name' => mb_strimwidth($registration->workshop->title, 0, 50, '...'),
                ],
            ],
            'customer_details' => [
                'first_name' => $registration->user->name,
                'email' => $registration->user->email,
            ],
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * Check transaction status directly from Midtrans API.
     */
    public function checkStatus(string $orderId): object
    {
        return Transaction::status($orderId);
    }

    /**
     * Map Midtrans transaction status string to internal registration status.
     * settlement / capture (fraud accept) -> paid
     * deny / cancel / failure -> failed
     * expire -> expired
     * pending -> pending
     */
    public function mapStatus(string $transactionStatus, ?string $fraudStatus = null): string
    {
        if ($transactionStatus === 'capture') {
            return ($fraudStatus === 'challenge') ? 'pending' : 'paid';
        }

        return match ($transactionStatus) {
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny', 'cancel', 'failure' => 'failed',
            'expire' => 'expired',
            default => 'pending',
        };
    }

    /**
     * Apply transaction result to registration idempotently.
     * On paid: set paid_at, generate unique ticket_code (WTX-XXXXXXXX).
     */
    public function applyTransactionResult(Registration $registration, string $newStatus): Registration
    {
        // Idempotency: if already paid, do not re-generate ticket_code or overwrite paid_at
        if ($registration->status === 'paid' && $newStatus === 'paid') {
            return $registration;
        }

        $data = [
            'status' => $newStatus,
        ];

        if ($newStatus === 'paid') {
            if (empty($registration->paid_at)) {
                $data['paid_at'] = now();
            }

            if (empty($registration->ticket_code)) {
                $data['ticket_code'] = $this->generateUniqueTicketCode();
            }
        }

        $registration->update($data);

        return $registration->fresh();
    }

    /**
     * Verify Midtrans notification signature key.
     * signature_key = sha512(order_id + status_code + gross_amount + server_key)
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $calculated = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        return hash_equals($calculated, $signatureKey);
    }

    /**
     * Generate unique ticket code format WTX-XXXXXXXX.
     */
    public function generateUniqueTicketCode(): string
    {
        do {
            $code = 'WTX-' . strtoupper(Str::random(8));
        } while (Registration::where('ticket_code', $code)->exists());

        return $code;
    }
}