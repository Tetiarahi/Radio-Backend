<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\RadioStation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StationController extends Controller
{
    /**
     * Display a listing of the radio stations.
     */
    public function index(Request $request): View
    {
        $query = RadioStation::with('activeStreams')->orderBy('sort_order');

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('band', 'like', "%{$search}%")
                  ->orWhere('frequency', 'like', "%{$search}%");
        }

        $stations = $query->paginate(10)->withQueryString();

        return view('admin.stations.index', compact('stations'));
    }

    /**
     * Show the form for creating a new station.
     */
    public function create(): View
    {
        return view('admin.stations.create');
    }

    /**
     * Store a newly created station in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'tagline'     => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'frequency'   => ['nullable', 'string', 'max:20'],
            'band'        => ['required', 'in:AM,FM,ONLINE'],
            'genre'       => ['nullable', 'string', 'max:50'],
            'language'    => ['nullable', 'string', 'max:50'],
            'country'     => ['nullable', 'string', 'max:50'],
            'timezone'    => ['nullable', 'string', 'max:50'],
            'sort_order'  => ['nullable', 'integer'],
            'is_active'   => ['boolean'],
            'logo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('stations/logos', 'public');
        }

        RadioStation::create($validated);

        return redirect()->route('admin.stations.index')
            ->with('success', 'Radio station created successfully.');
    }

    /**
     * Show the form for editing the specified station.
     */
    public function edit(RadioStation $station): View
    {
        return view('admin.stations.edit', compact('station'));
    }

    /**
     * Update the specified station in storage.
     */
    public function update(Request $request, RadioStation $station): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'tagline'     => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'frequency'   => ['nullable', 'string', 'max:20'],
            'band'        => ['required', 'in:AM,FM,ONLINE'],
            'genre'       => ['nullable', 'string', 'max:50'],
            'language'    => ['nullable', 'string', 'max:50'],
            'country'     => ['nullable', 'string', 'max:50'],
            'timezone'    => ['nullable', 'string', 'max:50'],
            'sort_order'  => ['nullable', 'integer'],
            'logo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            if ($station->logo_path) {
                Storage::disk('public')->delete($station->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('stations/logos', 'public');
        }

        $station->update($validated);

        return redirect()->route('admin.stations.index')
            ->with('success', 'Radio station updated successfully.');
    }

    /**
     * Remove the specified station from storage.
     */
    public function destroy(RadioStation $station): RedirectResponse
    {
        if ($station->logo_path) {
            Storage::disk('public')->delete($station->logo_path);
        }

        $station->delete();

        return redirect()->route('admin.stations.index')
            ->with('success', 'Radio station deleted successfully.');
    }
}
