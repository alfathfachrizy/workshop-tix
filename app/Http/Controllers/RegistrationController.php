<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Workshop;
use App\Services\MidtransService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    /**
     * Store a new workshop registration.
     */
    public function store(Request $request, Workshop $workshop): RedirectResponse
    {
        $user = $request->user();

        // 1. Check existing registration (duplicate check)
        $existing = Registration::where('user_id', $user->id)
            ->where('workshop_id', $workshop->id)
            ->first();

        if ($existing) {
            if ($existing->isPaid()) {
                return redirect()->route('tickets.show', $existing->id)
                    ->with('info', 'Anda sudah terdaftar dan lunas untuk workshop ini.');
            }

            if ($existing->isPending()) {
                return redirect()->route('registrations.show', $existing->id)
                    ->with('warning', 'Anda sudah memiliki pendaftaran yang menunggu pembayaran. Silakan selesaikan pembayaran Anda.');
            }

            return redirect()->route('registrations.show', $existing->id)
                ->with('error', 'Pendaftaran Anda sebelumnya berstatus ' . $existing->status . '. Silakan hubungi panitia jika membutuhkan bantuan.');
        }

        // 2. Check quota
        if ($workshop->isFull()) {
            return back()->with('error', 'Maaf, kuota untuk workshop ini sudah penuh.');
        }

        // 3. Create registration with unique order_id: WTX-{workshop_id}-{time}
        $orderId = "WTX-{$workshop->id}-" . time() . '-' . rand(100, 999);

        // Price comes strictly from DB workshops.price, never from request!
        $registration = Registration::create([
            'user_id' => $user->id,
            'workshop_id' => $workshop->id,
            'order_id' => $orderId,
            'status' => 'pending',
            'paid_at' => null,
            'ticket_code' => null,
        ]);

        // Attempt Snap token generation if MidtransService is configured
        try {
            $midtrans = app(MidtransService::class);
            $token = $midtrans->createSnapToken($registration);
            $registration->update(['snap_token' => $token]);
        } catch (\Throwable $e) {
            // Keep registration pending even if offline/sandbox error occurs
            logger()->warning('Midtrans token generation error: ' . $e->getMessage());
        }

        return redirect()->route('registrations.show', $registration->id)
            ->with('success', 'Pendaftaran berhasil dibuat! Silakan lakukan pembayaran untuk mendapatkan tiket digital.');
    }

    /**
     * Display user's own registrations.
     */
    public function myRegistrations(Request $request): View
    {
        $registrations = $request->user()->registrations()
            ->with('workshop')
            ->latest()
            ->get();

        return view('registrations.my', compact('registrations'));
    }

    /**
     * Display registration detail / payment page.
     */
    public function show(Registration $registration): View|RedirectResponse
    {
        Gate::authorize('view', $registration);

        $registration->load('workshop', 'user');

        // If already paid, can redirect directly to ticket
        if ($registration->isPaid()) {
            return redirect()->route('tickets.show', $registration->id);
        }

        // Ensure snap token is available if pending
        if ($registration->isPending() && empty($registration->snap_token)) {
            try {
                $midtrans = app(MidtransService::class);
                $token = $midtrans->createSnapToken($registration);
                $registration->update(['snap_token' => $token]);
            } catch (\Throwable $e) {
                logger()->warning('Midtrans snap_token error: ' . $e->getMessage());
            }
        }

        return view('registrations.show', compact('registration'));
    }
}