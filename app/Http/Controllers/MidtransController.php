<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MidtransController extends Controller
{
    /**
     * Handle incoming Midtrans Webhook Notification.
     * CSRF-exempt, verifies signature key, maps and updates registration idempotently.
     */
    public function handleNotification(Request $request, MidtransService $midtransService): JsonResponse
    {
        $orderId = (string) $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signatureKey = (string) $request->input('signature_key');
        $transactionStatus = (string) $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');

        if (! $midtransService->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            return response()->json(['message' => 'Invalid signature key'], 400);
        }

        $registration = Registration::where('order_id', $orderId)->first();
        if (! $registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        $newStatus = $midtransService->mapStatus($transactionStatus, $fraudStatus);
        $midtransService->applyTransactionResult($registration, $newStatus);

        return response()->json(['status' => 'OK', 'message' => 'Notification processed successfully']);
    }

    /**
     * Manually check transaction status via Midtrans API ("Cek status" button).
     */
    public function checkStatus(Registration $registration, MidtransService $midtransService): RedirectResponse
    {
        Gate::authorize('view', $registration);

        try {
            $midtransStatus = $midtransService->checkStatus($registration->order_id);

            $transactionStatus = $midtransStatus->transaction_status ?? null;
            $fraudStatus = $midtransStatus->fraud_status ?? null;

            if ($transactionStatus) {
                $newStatus = $midtransService->mapStatus($transactionStatus, $fraudStatus);
                $midtransService->applyTransactionResult($registration, $newStatus);

                if ($newStatus === 'paid') {
                    return redirect()->route('tickets.show', $registration->id)
                        ->with('success', 'Pembayaran berhasil diverifikasi! Tiket digital Anda telah terbit.');
                }

                return back()->with('info', "Status transaksi saat ini: {$newStatus}.");
            }

            return back()->with('warning', 'Transaksi belum ditemukan di sistem Midtrans. Silakan selesaikan pembayaran terlebih dahulu.');
        } catch (\Throwable $e) {
            return back()->with('warning', 'Belum ada transaksi pembayaran yang tercatat di Midtrans untuk order ini.');
        }
    }
}