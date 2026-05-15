<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StreamConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminStreamController extends Controller
{
    public function index(): JsonResponse
    {
        $streams = StreamConfig::with('station')->latest()->paginate(20);
        return response()->json(['success' => true, 'data' => $streams]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'radio_station_id' => ['required', 'exists:radio_stations,id'],
            'label'            => ['required', 'string', 'max:100'],
            'stream_url'       => ['required', 'url'],
            'stream_type'      => ['required', 'in:icecast,shoutcast,hls,other'],
            'codec'            => ['nullable', 'string', 'max:20'],
            'bitrate'          => ['nullable', 'integer', 'min:8', 'max:1024'],
            'is_https'         => ['boolean'],
            'is_default'       => ['boolean'],
            'is_active'        => ['boolean'],
            'metadata_url'     => ['nullable', 'url'],
        ]);

        // Ensure only one default stream per station
        if ($validated['is_default'] ?? false) {
            StreamConfig::where('radio_station_id', $validated['radio_station_id'])
                ->update(['is_default' => false]);
        }

        $stream = StreamConfig::create($validated);

        return response()->json(['success' => true, 'data' => $stream], 201);
    }

    public function show(StreamConfig $stream): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $stream->load('station')]);
    }

    public function update(Request $request, StreamConfig $stream): JsonResponse
    {
        $validated = $request->validate([
            'label'        => ['sometimes', 'string', 'max:100'],
            'stream_url'   => ['sometimes', 'url'],
            'stream_type'  => ['sometimes', 'in:icecast,shoutcast,hls,other'],
            'codec'        => ['nullable', 'string', 'max:20'],
            'bitrate'      => ['nullable', 'integer'],
            'is_https'     => ['boolean'],
            'is_default'   => ['boolean'],
            'is_active'    => ['boolean'],
            'metadata_url' => ['nullable', 'url'],
        ]);

        if ($validated['is_default'] ?? false) {
            StreamConfig::where('radio_station_id', $stream->radio_station_id)
                ->where('id', '!=', $stream->id)
                ->update(['is_default' => false]);
        }

        $stream->update($validated);

        return response()->json(['success' => true, 'data' => $stream]);
    }

    public function destroy(StreamConfig $stream): JsonResponse
    {
        $stream->delete();
        return response()->json(['success' => true, 'message' => 'Stream deleted.']);
    }
}
