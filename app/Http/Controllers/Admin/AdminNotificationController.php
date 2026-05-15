<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use App\Services\PushNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function __construct(private readonly PushNotificationService $pushService)
    {
    }

    public function index(): JsonResponse
    {
        $notifications = PushNotification::with('creator')
            ->latest()
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $notifications]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:100'],
            'body'            => ['required', 'string', 'max:500'],
            'image_url'       => ['nullable', 'url'],
            'data'            => ['nullable', 'array'],
            'target_audience' => ['required', 'in:all,android,ios'],
            'scheduled_at'    => ['nullable', 'date', 'after:now'],
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['status'] = isset($validated['scheduled_at']) ? 'scheduled' : 'draft';

        $notification = PushNotification::create($validated);

        return response()->json(['success' => true, 'data' => $notification], 201);
    }

    public function show(PushNotification $notification): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $notification->load('creator')]);
    }

    public function update(Request $request, PushNotification $notification): JsonResponse
    {
        if (in_array($notification->status, ['sent'])) {
            return response()->json(['success' => false, 'message' => 'Cannot edit a sent notification.'], 422);
        }

        $validated = $request->validate([
            'title'           => ['sometimes', 'string', 'max:100'],
            'body'            => ['sometimes', 'string', 'max:500'],
            'image_url'       => ['nullable', 'url'],
            'data'            => ['nullable', 'array'],
            'target_audience' => ['sometimes', 'in:all,android,ios'],
            'scheduled_at'    => ['nullable', 'date', 'after:now'],
        ]);

        $notification->update($validated);

        return response()->json(['success' => true, 'data' => $notification]);
    }

    public function destroy(PushNotification $notification): JsonResponse
    {
        $notification->delete();
        return response()->json(['success' => true, 'message' => 'Notification deleted.']);
    }

    /**
     * POST /api/v1/admin/notifications/{notification}/send
     * Immediately sends the notification regardless of schedule.
     */
    public function send(PushNotification $notification): JsonResponse
    {
        if ($notification->status === 'sent') {
            return response()->json(['success' => false, 'message' => 'Notification already sent.'], 422);
        }

        $this->pushService->send($notification);
        $notification->refresh();

        return response()->json([
            'success' => true,
            'message' => "Notification sent to {$notification->recipients_count} devices.",
            'data'    => $notification,
        ]);
    }
}
