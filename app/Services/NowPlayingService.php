<?php

namespace App\Services;

use App\Models\StreamConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NowPlayingService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout'         => 5,
            'connect_timeout' => 3,
            'verify'          => false, // Some stream servers have self-signed certs
        ]);
    }

    /**
     * Fetches "now playing" metadata from an Icecast or Shoutcast server.
     * Results are cached for 30 seconds to avoid hammering the stream server.
     */
    public function fetch(StreamConfig $stream): array
    {
        $cacheKey = "now_playing_{$stream->id}";

        return Cache::remember($cacheKey, 30, function () use ($stream) {
            return match ($stream->stream_type) {
                'icecast'   => $this->fetchIcecast($stream),
                'shoutcast' => $this->fetchShoutcast($stream),
                default     => $this->emptyMetadata(),
            };
        });
    }

    // ─── Icecast ─────────────────────────────────────────────────────────────

    /**
     * Icecast 2.x — uses /status-json.xsl endpoint.
     */
    private function fetchIcecast(StreamConfig $stream): array
    {
        try {
            $url = $stream->metadata_url;
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);

            // Find the matching mountpoint
            $sources = $data['icestats']['source'] ?? [];
            // Normalize to array of sources
            if (isset($sources['listenurl'])) {
                $sources = [$sources];
            }

            $streamPath = parse_url($stream->stream_url, PHP_URL_PATH);
            $source = collect($sources)->first(fn ($s) => ($s['listenurl'] ?? '') === $stream->stream_url)
                ?? collect($sources)->first()
                ?? [];

            $title  = $source['title'] ?? null;
            $artist = null;
            $song   = $title;

            // Try to split "Artist - Song" format
            if ($title && str_contains($title, ' - ')) {
                [$artist, $song] = explode(' - ', $title, 2);
            }

            return [
                'title'     => $title,
                'artist'    => $artist ? trim($artist) : null,
                'song'      => $song ? trim($song) : null,
                'listeners' => $source['listeners'] ?? null,
                'genre'     => $source['genre'] ?? null,
                'server_type' => 'icecast',
            ];
        } catch (GuzzleException $e) {
            Log::warning("NowPlayingService: Icecast fetch failed for stream {$stream->id}: {$e->getMessage()}");
            return $this->emptyMetadata();
        }
    }

    // ─── Shoutcast ───────────────────────────────────────────────────────────

    /**
     * Shoutcast v2 — uses /currentsong?sid=1 and /stats?sid=1 endpoints.
     */
    private function fetchShoutcast(StreamConfig $stream): array
    {
        try {
            $baseUrl = $stream->metadata_url; // e.g. http://server:8000

            $songResponse  = $this->client->get("{$baseUrl}/currentsong?sid=1");
            $statsResponse = $this->client->get("{$baseUrl}/stats?sid=1");

            $currentSong = trim($songResponse->getBody());
            $statsXml    = simplexml_load_string($statsResponse->getBody());

            $listeners = (int) ($statsXml->CURRENTLISTENERS ?? 0);
            $artist    = null;
            $song      = $currentSong;

            if (str_contains($currentSong, ' - ')) {
                [$artist, $song] = explode(' - ', $currentSong, 2);
            }

            return [
                'title'     => $currentSong ?: null,
                'artist'    => $artist ? trim($artist) : null,
                'song'      => $song ? trim($song) : null,
                'listeners' => $listeners,
                'genre'     => null,
                'server_type' => 'shoutcast',
            ];
        } catch (\Exception $e) {
            Log::warning("NowPlayingService: Shoutcast fetch failed for stream {$stream->id}: {$e->getMessage()}");
            return $this->emptyMetadata();
        }
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function emptyMetadata(): array
    {
        return [
            'title'     => null,
            'artist'    => null,
            'song'      => null,
            'listeners' => null,
            'genre'     => null,
            'server_type' => null,
        ];
    }
}
