@extends('layouts.admin')

@section('title', 'Stream Configurations')

@section('content')
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px;">
        <form action="{{ route('admin.streams.index') }}" method="GET" style="display: flex; gap: 16px; width: 100%; max-width: 400px;">
            <input type="text" name="search" class="form-control" placeholder="Search streams..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-secondary">Search</button>
            @if (request('search'))
                <a href="{{ route('admin.streams.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.streams.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i>
            <span>Add Stream Config</span>
        </a>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Station</th>
                    <th>Label</th>
                    <th>Stream URL</th>
                    <th>Codec / Bitrate</th>
                    <th>Default</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($streams as $stream)
                    <tr>
                        <td style="font-weight: 600;">{{ $stream->station->name ?? 'Unknown Station' }}</td>
                        <td style="font-weight: 500;">{{ $stream->label }}</td>
                        <td>
                            <div style="max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-family: monospace; font-size: 13px;" title="{{ $stream->stream_url }}">
                                {{ $stream->stream_url }}
                            </div>
                            @if ($stream->metadata_url)
                                <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;" title="Metadata: {{ $stream->metadata_url }}">
                                    <i class="fa-solid fa-link" style="font-size: 10px;"></i> Metadata URL configured
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 500; text-transform: uppercase;">{{ $stream->codec }}</div>
                            <div style="font-size: 13px; color: var(--text-secondary);">{{ $stream->bitrate }} kbps</div>
                        </td>
                        <td>
                            @if ($stream->is_default)
                                <span class="badge badge-success">Default</span>
                            @else
                                <span style="color: var(--text-muted); font-size: 13px;">—</span>
                            @endif
                        </td>
                        <td>
                            @if ($stream->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-warning">Inactive</span>
                            @endif
                        </td>
                        <td style="display: flex; gap: 8px;">
                            <a href="{{ route('admin.streams.edit', $stream) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 13px;">Edit</a>
                            
                            <form action="{{ route('admin.streams.destroy', $stream) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this stream configuration?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 13px;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-secondary);">No stream configurations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    {{ $streams->links() }}
@endsection
