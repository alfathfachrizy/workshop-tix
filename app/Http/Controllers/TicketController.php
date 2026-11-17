<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    /**
     * Display digital workshop ticket.
     * Accessible by owner AND paid status only.
     */
    public function show(Registration $registration): View|RedirectResponse
    {
        Gate::authorize('viewTicket', $registration);

        if (! $registration->isPaid()) {
            return redirect()->route('registrations.show', $registration->id)
                ->with('warning', 'Tiket digital hanya dapat diakses setelah pendaftaran berstatus lunas.');
        }

        $registration->load('workshop', 'user');

        // Generate QR code SVG from ticket_code
        $qrCode = QrCode::size(180)
            ->color(30, 58, 138) // Primary navy #1e3a8a
            ->margin(1)
            ->generate($registration->ticket_code);

        return view('tickets.show', compact('registration', 'qrCode'));
    }
}