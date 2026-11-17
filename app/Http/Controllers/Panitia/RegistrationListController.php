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
     * Display all registrations with N+1 comparison demo.
     * Mode eager (default) vs lazy.
     */
    public function index(Request $request): View
    {
        $mode = $request->query('mode', 'eager');
        if (! in_array($mode, ['eager', 'lazy'])) {
            $mode = 'eager';
        }

        $queryStats = (object) [
            'count' => 0,
            'time' => 0,
            'queries' => [],
        ];

        DB::listen(function ($query) use ($queryStats) {
            $queryStats->count++;
            $queryStats->time += $query->time;
            $queryStats->queries[] = $query->sql;
        });

        if ($mode === 'lazy') {
            // Deliberately lazy load: no with()
            // Queries will execute one-by-one inside the Blade loop (N+1 Problem)
            $registrations = Registration::latest()->get();
        } else {
            // Optimized eager loading: solves N+1 problem with only 3 queries
            $registrations = Registration::with(['user', 'workshop'])->latest()->get();
        }

        $totalRecords = $registrations->count();
        // Estimated queries comparison:
        // Lazy: 1 (registrations) + N (users) + N (workshops) = 2N + 1
        $theoreticalLazyQueries = ($totalRecords * 2) + 1;
        // Eager: 1 (registrations) + 1 (users whereIn) + 1 (workshops whereIn) = 3
        $theoreticalEagerQueries = $totalRecords > 0 ? 3 : 1;

        return view('panitia.registrations.index', [
            'registrations' => $registrations,
            'queryStats' => $queryStats,
            'mode' => $mode,
            'totalRecords' => $totalRecords,
            'theoreticalLazyQueries' => $theoreticalLazyQueries,
            'theoreticalEagerQueries' => $theoreticalEagerQueries,
        ]);
    }
}