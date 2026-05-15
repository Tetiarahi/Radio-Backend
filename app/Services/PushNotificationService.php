<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\PushNotification;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    private Client $client;
    private string $expoPushUrl;

    public function __construct()
    {
        $this->client = new Client(['timeout' => 15]);
        $this->expoPushUrl = env('EXPO_PUSH_BASE_URL', 'https://exp.host/--/api/v2/push/send');
    }

    /**
     * Sends a push notification to all active device tokens (or filtered by platform).
     */
    public function send(PushNotification $notification): void
    {
        $query = DeviceToken::active();

        if ($notification->target_audience !== 'all') {
            $query->byPlatform($notification->target_audience);
        }

        $tokens = $query->pluck('expo_push_token')->toArray();

        if (empty($tokens)) {
            $notification->update([
                'status'           => 'sent',
                'sent_at'          => now(),
                'recipients_count' => 0,
            ]);
            return;
        }

        // Expo accepts up to 100 tokens per request — batch them
        $chunks = array_chunk($tokens, 100);
        $totalSent = 0;
        $errors = [];

        foreach ($chunks as $chunk) {
            $messages = array_map(fn (string $token) => $this->buildMessage($token, $notification), $chunk);

            try {
                $response = $this->client->post($this->expoPushUrl, [
                    'headers' => [
                        'Accept'       => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $messages,
                ]);

                $body = json_decode($response->getBody(), true);
                $results = $body['data'] ?? [];

                foreach ($results as $result) {
                    if ($result['status'] === 'ok') {
                        $totalSent++;
                    } else {
                        $errors[] = $result['message'] ?? 'Unknown error';
                    }
                }
            } catch (GuzzleException $e) {
                Log::error("PushNotificationService: Failed to send batch. {$e->getMessage()}");
                $errors[] = $e->getMessage();
            }
        }

        $notification->update([
            'status'           => empty($errors) ? 'sent' : 'failed',
            'sent_at'          => now(),
            'recipients_count' => $totalSent,
            'error_message'    => ! empty($errors) ? implode('; ', array_unique($errors)) : null,
        ]);
    }

    // ─── Message builder ──────────────────────────────────────────────────────

    private function buildMessage(string $token, PushNotification $notification): array
    {
        $message = [
            'to'    => $token,
            'title' => $notification->title,
            'body'  => $notification->body,
            'sound' => 'default',
            'data'  => $notification->data ?? [],
            '_contentAvailable' => true,
        ];

        if ($notification->image_url) {
            $message['image'] = $notification->image_url;
        }

        return $message;
    }
}
