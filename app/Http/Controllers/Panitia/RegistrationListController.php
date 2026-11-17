<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegistrationListController extends Controller
{
    /**
     * Display all registrations with query tracking.
     * Initial implementation (Before): Deliberately lazy loading.
     */
    public function index(Request $request): View
    {
        $queries = [];
        $totalTimeMs = 0;

        DB::listen(function ($query) use (&$queries, &$totalTimeMs) {
            $queries[] = $query->sql;
            $totalTimeMs += $query->time;
        });

        // Deliberately unoptimized lazy loading
        $registrations = Registration::all();

        return view('panitia.registrations.index', [
            'registrations' => $registrations,
            'queryCount' => count($queries),
            'totalTimeMs' => $totalTimeMs,
            'mode' => 'lazy',
        ]);
    }
}