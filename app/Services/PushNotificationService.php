<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\PushNotification;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\Middleware\AuthTokenMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    private Client $client;
    private string $fcmUrl;
    private bool $isConfigured = false;

    public function __construct()
    {
        $credentialsPath = base_path(env('FIREBASE_CREDENTIALS', 'firebase_credentials.json'));
        if (file_exists($credentialsPath)) {
            $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
            $credentials = new ServiceAccountCredentials($scopes, $credentialsPath);
            $middleware = new AuthTokenMiddleware($credentials);
            $stack = HandlerStack::create();
            $stack->push($middleware);

            $this->client = new Client([
                'handler' => $stack,
                'auth' => 'google_auth',
                'timeout' => 15
            ]);

            $projectId = env('FIREBASE_PROJECT_ID', 'your-project-id');
            $this->fcmUrl = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
            $this->isConfigured = true;
        } else {
            $this->client = new Client(['timeout' => 15]);
            Log::warning("Firebase credentials not found at {$credentialsPath}");
        }
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

        if (!$this->isConfigured) {
            Log::error("PushNotificationService: Firebase not configured.");
            $notification->update(['status' => 'failed', 'error_message' => 'Firebase not configured']);
            return;
        }

        $totalSent = 0;
        $errors = [];

        foreach ($tokens as $token) {
            $message = $this->buildMessage($token, $notification);

            try {
                $response = $this->client->post($this->fcmUrl, [
                    'headers' => [
                        'Accept'       => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                    'json' => ['message' => $message],
                ]);

                if ($response->getStatusCode() === 200) {
                    $totalSent++;
                }
            } catch (GuzzleException $e) {
                Log::error("PushNotificationService: Failed to send to token {$token}. {$e->getMessage()}");
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
            'token' => $token,
            'notification' => [
                'title' => $notification->title,
                'body'  => $notification->body,
            ],
            'data' => array_map('strval', $notification->data ?? [])
        ];

        if ($notification->image_url) {
            $message['notification']['image'] = $notification->image_url;
        }

        return $message;
    }
}
