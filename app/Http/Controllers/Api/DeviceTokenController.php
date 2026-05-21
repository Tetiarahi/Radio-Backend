<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeviceTokenController extends Controller
{
    /**
     * POST /api/v1/device-tokens
     * Registers or refreshes a device's Expo push token.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fcm_token'   => ['required', 'string'],
            'platform'    => ['required', Rule::in(['android', 'ios', 'unknown'])],
            'app_version' => ['nullable', 'string', 'max:20'],
            'locale'      => ['nullable', 'string', 'max:10'],
        ]);

        $token = DeviceToken::updateOrCreate(
            ['fcm_token' => $validated['fcm_token']],
            [
                'platform'     => $validated['platform'],
                'app_version'  => $validated['app_version'] ?? null,
                'locale'       => $validated['locale'] ?? 'en',
                'is_active'    => true,
                'last_seen_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Device token registered.',
            'data'    => ['id' => $token->id],
        ], 201);
    }

    /**
     * DELETE /api/v1/device-tokens/{token}
     * Unregisters a device (user opted out of notifications).
     */
    public function destroy(string $pushToken): JsonResponse
    {
        DeviceToken::where('fcm_token', $pushToken)
            ->update(['is_active' => false]);

        return response()->json(['success' => true, 'message' => 'Token deactivated.']);
    }
}
