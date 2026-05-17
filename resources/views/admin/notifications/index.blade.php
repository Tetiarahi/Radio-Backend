@extends('layouts.admin')

@section('title', 'Push Notifications')

@section('content')
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px;">
        <form action="{{ route('admin.notifications.index') }}" method="GET" style="display: flex; gap: 16px; width: 100%; max-width: 400px;">
            <input type="text" name="search" class="form-control" placeholder="Search notifications..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-secondary">Search</button>
            @if (request('search'))
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i>
            <span>Create Notification</span>
        </a>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Title / Body</th>
                    <th>Target Audience</th>
                    <th>Status</th>
                    <th>Recipients</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($notifications as $notification)
                    <tr>
                        <td>
                            <div style="font-weight: 600; font-size: 16px;">{{ $notification->title }}</div>
                            <div style="font-size: 13px; color: var(--text-secondary); max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $notification->body }}">
                                {{ $notification->body }}
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-secondary">{{ strtoupper($notification->target_audience) }}</span>
                        </td>
                        <td>
                            @if ($notification->status === 'sent')
                                <span class="badge badge-success">Sent</span>
                            @elseif ($notification->status === 'draft')
                                <span class="badge badge-secondary">Draft</span>
                            @else
                                <span class="badge badge-warning">{{ ucfirst($notification->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $notification->recipients_count ?? 0 }} devices</td>
                        <td>
                            <div style="font-weight: 500;">{{ $notification->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 12px; color: var(--text-secondary);">{{ $notification->created_at->format('g:i A') }}</div>
                        </td>
                        <td>
                            <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this notification record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 13px;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-secondary);">No push notifications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    {{ $notifications->links() }}
@endsection
