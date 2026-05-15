<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StreamConfig;
use App\Services\NowPlayingService;
use Illuminate\Http\JsonResponse;

class NowPlayingController extends Controller
{
    public function __construct(private readonly NowPlayingService $nowPlayingService)
    {
    }

    /**
     * GET /api/v1/now-playing/{streamId}
     * Fetches live stream metadata from Icecast/Shoutcast.
     */
    public function show(StreamConfig $stream): JsonResponse
    {
        if (! $stream->metadata_url) {
            return response()->json([
                'success' => true,
                'data' => [
                    'title'   => null,
                    'artist'  => null,
                    'song'    => null,
                    'listeners' => null,
                ],
            ]);
        }

        $metadata = $this->nowPlayingService->fetch($stream);

        return response()->json([
            'success' => true,
            'data' => $metadata,
        ]);
    }
}
