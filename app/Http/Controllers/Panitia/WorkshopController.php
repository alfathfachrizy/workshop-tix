<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkshopRequest;
use App\Models\Workshop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WorkshopController extends Controller
{
    /**
     * Display a listing of the workshops.
     */
    public function index(Request $request): View
    {
        $query = Workshop::withCount([
            'registrations as total_registrations',
            'registrations as paid_registrations' => function ($q) {
                $q->where('status', 'paid');
            },
        ]);

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('speaker', 'like', "%{$search}%");
        }

        $workshops = $query->latest()->paginate(10)->withQueryString();

        return view('panitia.workshops.index', compact('workshops'));
    }

    /**
     * Show the form for creating a new workshop.
     */
    public function create(): View
    {
        return view('panitia.workshops.create');
    }

    /**
     * Store a newly created workshop in storage.
     */
    public function store(WorkshopRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $baseSlug = Str::slug($data['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Workshop::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
            $data['slug'] = $slug;
        }

        Workshop::create($data);

        return redirect()->route('panitia.workshops.index')
            ->with('success', 'Workshop berhasil ditambahkan ke sistem.');
    }

    /**
     * Show the form for editing the specified workshop.
     */
    public function edit(Workshop $workshop): View
    {
        return view('panitia.workshops.edit', compact('workshop'));
    }

    /**
     * Update the specified workshop in storage.
     */
    public function update(WorkshopRequest $request, Workshop $workshop): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $workshop->update($data);

        return redirect()->route('panitia.workshops.index')
            ->with('success', 'Data workshop berhasil diperbarui.');
    }

    /**
     * Remove the specified workshop from storage.
     */
    public function destroy(Workshop $workshop): RedirectResponse
    {
        $title = $workshop->title;
        $workshop->delete();

        return redirect()->route('panitia.workshops.index')
            ->with('success', "Workshop \"{$title}\" berhasil dihapus.");
    }

    /**
     * Display registrants for a specific workshop.
     */
    public function registrants(Workshop $workshop): View
    {
        $registrations = $workshop->registrations()
            ->with('user')
            ->latest()
            ->get();

        return view('panitia.workshops.registrants', compact('workshop', 'registrations'));
    }
}