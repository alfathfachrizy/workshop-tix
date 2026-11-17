<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicWorkshopController extends Controller
{
    /**
     * Display a listing of workshops.
     */
    public function index(Request $request): View
    {
        $query = Workshop::withCount(['registrations as paid_registrations_count' => function ($q) {
            $q->where('status', 'paid');
        }]);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('speaker', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($maxPrice = $request->input('max_price')) {
            $query->where('price', '<=', (int) $maxPrice);
        }

        if ($date = $request->input('date')) {
            $query->whereDate('starts_at', $date);
        }

        $workshops = $query->orderBy('starts_at', 'asc')->get();

        return view('welcome', compact('workshops'));
    }

    /**
     * Display the specified workshop detail.
     */
    public function show(string $slug): View
    {
        $workshop = Workshop::withCount(['registrations as paid_registrations_count' => function ($q) {
            $q->where('status', 'paid');
        }])->where('slug', $slug)->firstOrFail();

        $userRegistration = null;
        if (auth()->check()) {
            $userRegistration = auth()->user()->registrations()
                ->where('workshop_id', $workshop->id)
                ->first();
        }

        return view('workshops.show', compact('workshop', 'userRegistration'));
    }
}