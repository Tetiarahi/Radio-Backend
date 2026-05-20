<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use App\Services\PushNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display a listing of the push notifications.
     */
    public function index(Request $request): View
    {
        $query = PushNotification::with('creator')->orderBy('id', 'desc');

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
        }

        $notifications = $query->paginate(10)->withQueryString();

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new push notification.
     */
    public function create(): View
    {
        return view('admin.notifications.create');
    }

    /**
     * Store and optionally dispatch a push notification.
     */
    public function store(Request $request, PushNotificationService $service): RedirectResponse
    {
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:100'],
            'body'            => ['required', 'string', 'max:500'],
            'image_url'       => ['nullable', 'url'],
            'target_audience' => ['required', 'in:all,android,ios'],
            'action'          => ['required', 'in:draft,send'],
        ]);

        $notification = PushNotification::create([
            'title'           => $validated['title'],
            'body'            => $validated['body'],
            'image_url'       => $validated['image_url'],
            'target_audience' => $validated['target_audience'],
            'status'          => 'draft',
            'created_by'      => Auth::id(),
        ]);

        if ($validated['action'] === 'send') {
            $service->send($notification);
            return redirect()->route('admin.notifications.index')
                ->with('success', "Push notification dispatched! Sent to {$notification->recipients_count} devices.");
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Push notification saved as draft.');
    }

    /**
     * Remove the specified push notification from storage.
     */
    public function destroy(PushNotification $notification): RedirectResponse
    {
        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Push notification deleted successfully.');
    }
}
