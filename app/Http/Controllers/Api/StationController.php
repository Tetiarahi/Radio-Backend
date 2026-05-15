<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RadioStation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * GET /api/v1/stations
     * Returns all active stations with their default stream.
     */
    public function index(Request $request): JsonResponse
    {
        $query = RadioStation::with(['defaultStream'])
            ->active();

        // Filter by band (AM/FM/ONLINE)
        if ($request->has('band')) {
            $query->byBand($request->band);
        }

        $stations = $query->get()->map(fn ($s) => $this->formatStation($s));

        return response()->json([
            'success' => true,
            'data' => $stations,
        ]);
    }

    /**
     * GET /api/v1/stations/{id}
     * Returns a single station with ALL its active streams.
     */
    public function show(RadioStation $station): JsonResponse
    {
        if (! $station->is_active) {
            return response()->json(['success' => false, 'message' => 'Station not found.'], 404);
        }

        $station->load('activeStreams');

        return response()->json([
            'success' => true,
            'data' => $this->formatStation($station, withAllStreams: true),
        ]);
    }

    // ─── Private helpers ─────────────────────────────────────────────────────

    private function formatStation(RadioStation $station, bool $withAllStreams = false): array
    {
        $data = [
            'id'          => $station->id,
            'name'        => $station->name,
            'slug'        => $station->slug,
            'tagline'     => $station->tagline,
            'description' => $station->description,
            'logo_url'    => $station->logo_url,
            'frequency'   => $station->frequency,
            'band'        => $station->band,
            'genre'       => $station->genre,
            'language'    => $station->language,
            'country'     => $station->country,
            'sort_order'  => $station->sort_order,
        ];

        if ($withAllStreams) {
            $data['streams'] = $station->activeStreams->map(fn ($stream) => $this->formatStream($stream));
        } else {
            $data['default_stream'] = $station->defaultStream
                ? $this->formatStream($station->defaultStream)
                : null;
        }

        return $data;
    }

    private function formatStream($stream): array
    {
        return [
            'id'          => $stream->id,
            'label'       => $stream->label,
            'stream_url'  => $stream->stream_url,
            'stream_type' => $stream->stream_type,
            'codec'       => $stream->codec,
            'bitrate'     => $stream->bitrate,
            'is_https'    => $stream->is_https,
            'is_default'  => $stream->is_default,
            'metadata_url'=> $stream->metadata_url,
        ];
    }
}
