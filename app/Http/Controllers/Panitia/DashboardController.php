<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Workshop;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the panitia dashboard with stat cards.
     */
    public function index(): View
    {
        $totalWorkshops = Workshop::count();
        $totalRegistrations = Registration::count();
        $totalPaid = Registration::where('status', 'paid')->count();
        $totalPending = Registration::where('status', 'pending')->count();

        // Revenue from all paid registrations
        $totalRevenue = Registration::where('status', 'paid')
            ->join('workshops', 'registrations.workshop_id', '=', 'workshops.id')
            ->sum('workshops.price');

        $recentRegistrations = Registration::with(['user', 'workshop'])
            ->latest()
            ->take(6)
            ->get();

        return view('panitia.dashboard', compact(
            'totalWorkshops',
            'totalRegistrations',
            'totalPaid',
            'totalPending',
            'totalRevenue',
            'recentRegistrations'
        ));
    }
}