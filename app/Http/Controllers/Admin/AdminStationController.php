<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RadioStation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminStationController extends Controller
{
    public function index(): JsonResponse
    {
        $stations = RadioStation::with('activeStreams')->orderBy('sort_order')->get();
        return response()->json(['success' => true, 'data' => $stations]);
    }

    public function store(Request $request): JsonResponse
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
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $station = RadioStation::create($validated);

        return response()->json(['success' => true, 'data' => $station], 201);
    }

    public function show(RadioStation $station): JsonResponse
    {
        $station->load('streams');
        return response()->json(['success' => true, 'data' => $station]);
    }

    public function update(Request $request, RadioStation $station): JsonResponse
    {
        $validated = $request->validate([
            'name'        => ['sometimes', 'string', 'max:100'],
            'tagline'     => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'frequency'   => ['nullable', 'string', 'max:20'],
            'band'        => ['sometimes', 'in:AM,FM,ONLINE'],
            'genre'       => ['nullable', 'string', 'max:50'],
            'language'    => ['nullable', 'string', 'max:50'],
            'country'     => ['nullable', 'string', 'max:50'],
            'timezone'    => ['nullable', 'string', 'max:50'],
            'sort_order'  => ['nullable', 'integer'],
            'is_active'   => ['boolean'],
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $station->update($validated);

        return response()->json(['success' => true, 'data' => $station]);
    }

    public function destroy(RadioStation $station): JsonResponse
    {
        // Delete logo from storage
        if ($station->logo_path) {
            if (str_starts_with($station->logo_path, 'uploads/')) {
                $oldPath = public_path($station->logo_path);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            } else {
                Storage::disk('public')->delete($station->logo_path);
            }
        }
        $station->delete();

        return response()->json(['success' => true, 'message' => 'Station deleted.']);
    }

    /**
     * POST /api/v1/admin/stations/{station}/logo
     */
    public function uploadLogo(Request $request, RadioStation $station): JsonResponse
    {
        $request->validate([
            'logo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
        ]);

        // Delete old logo
        if ($station->logo_path) {
            if (str_starts_with($station->logo_path, 'uploads/')) {
                $oldPath = public_path($station->logo_path);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            } else {
                Storage::disk('public')->delete($station->logo_path);
            }
        }

        $file = $request->file('logo');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('uploads/stations/' . $station->id . '/logos');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        $file->move($destinationPath, $filename);
        
        $path = 'uploads/stations/' . $station->id . '/logos/' . $filename;
        $station->update(['logo_path' => $path]);

        return response()->json([
            'success'  => true,
            'logo_url' => asset($path),
        ]);
    }
}
