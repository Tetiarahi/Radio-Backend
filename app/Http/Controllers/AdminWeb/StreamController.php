<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\RadioStation;
use App\Models\StreamConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StreamController extends Controller
{
    /**
     * Display a listing of the stream configs.
     */
    public function index(Request $request): View
    {
        $query = StreamConfig::with('station')->orderBy('id', 'desc');

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where('label', 'like', "%{$search}%")
                  ->orWhere('stream_url', 'like', "%{$search}%")
                  ->orWhere('codec', 'like', "%{$search}%");
        }

        $streams = $query->paginate(10)->withQueryString();

        return view('admin.streams.index', compact('streams'));
    }

    /**
     * Show the form for creating a new stream config.
     */
    public function create(): View
    {
        $stations = RadioStation::orderBy('sort_order')->get();
        return view('admin.streams.create', compact('stations'));
    }

    /**
     * Store a newly created stream config in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'radio_station_id' => ['required', 'exists:radio_stations,id'],
            'label'            => ['required', 'string', 'max:100'],
            'stream_url'       => ['required', 'url'],
            'stream_type'      => ['required', 'in:icecast,shoutcast,hls,raw'],
            'codec'            => ['required', 'string', 'max:20'],
            'bitrate'          => ['required', 'integer'],
            'metadata_url'     => ['nullable', 'url'],
        ]);

        $validated['is_https'] = str_starts_with($validated['stream_url'], 'https://');
        $validated['is_default'] = $request->has('is_default');
        $validated['is_active'] = $request->has('is_active');

        // If marked as default, unset other defaults for this station
        if ($validated['is_default']) {
            StreamConfig::where('radio_station_id', $validated['radio_station_id'])
                ->update(['is_default' => false]);
        }

        StreamConfig::create($validated);

        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream configuration created successfully.');
    }

    /**
     * Show the form for editing the specified stream config.
     */
    public function edit(StreamConfig $stream): View
    {
        $stations = RadioStation::orderBy('sort_order')->get();
        return view('admin.streams.edit', compact('stream', 'stations'));
    }

    /**
     * Update the specified stream config in storage.
     */
    public function update(Request $request, StreamConfig $stream): RedirectResponse
    {
        $validated = $request->validate([
            'radio_station_id' => ['required', 'exists:radio_stations,id'],
            'label'            => ['required', 'string', 'max:100'],
            'stream_url'       => ['required', 'url'],
            'stream_type'      => ['required', 'in:icecast,shoutcast,hls,raw'],
            'codec'            => ['required', 'string', 'max:20'],
            'bitrate'          => ['required', 'integer'],
            'metadata_url'     => ['nullable', 'url'],
        ]);

        $validated['is_https'] = str_starts_with($validated['stream_url'], 'https://');
        $validated['is_default'] = $request->has('is_default');
        $validated['is_active'] = $request->has('is_active');

        if ($validated['is_default'] && ! $stream->is_default) {
            StreamConfig::where('radio_station_id', $validated['radio_station_id'])
                ->where('id', '!=', $stream->id)
                ->update(['is_default' => false]);
        }

        $stream->update($validated);

        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream configuration updated successfully.');
    }

    /**
     * Remove the specified stream config from storage.
     */
    public function destroy(StreamConfig $stream): RedirectResponse
    {
        $stream->delete();

        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream configuration deleted successfully.');
    }
}
