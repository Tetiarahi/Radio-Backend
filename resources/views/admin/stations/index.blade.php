@extends('layouts.admin')

@section('title', 'Radio Stations')

@section('content')
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px;">
        <form action="{{ route('admin.stations.index') }}" method="GET" style="display: flex; gap: 16px; width: 100%; max-width: 400px;">
            <input type="text" name="search" class="form-control" placeholder="Search stations..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-secondary">Search</button>
            @if (request('search'))
                <a href="{{ route('admin.stations.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.stations.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i>
            <span>Add New Station</span>
        </a>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Logo / Name</th>
                    <th>Band / Freq</th>
                    <th>Streams</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stations as $station)
                    <tr>
                        <td style="display: flex; align-items: center; gap: 16px;">
                            @if ($station->logo_path)
                                <img src="{{ Storage::url($station->logo_path) }}" alt="{{ $station->name }}" style="width: 44px; height: 44px; border-radius: 12px; object-fit: cover;">
                            @else
                                <div style="width: 44px; height: 44px; border-radius: 12px; background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--brand-primary); font-size: 16px;">
                                    {{ substr($station->name, 0, 2) }}
                                </div>
                            @endif
                            <div>
                                <div style="font-weight: 600; font-size: 16px;">{{ $station->name }}</div>
                                <div style="font-size: 13px; color: var(--text-secondary);">{{ $station->tagline ?? 'No tagline' }}</div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 500;">{{ $station->band }}</div>
                            <div style="font-size: 13px; color: var(--text-secondary);">{{ $station->frequency ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <span class="badge badge-secondary">{{ $station->activeStreams->count() }} active</span>
                        </td>
                        <td>{{ $station->sort_order }}</td>
                        <td>
                            @if ($station->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-warning">Inactive</span>
                            @endif
                        </td>
                        <td style="display: flex; gap: 8px;">
                            <a href="{{ route('admin.stations.edit', $station) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 13px;">Edit</a>
                            
                            <form action="{{ route('admin.stations.destroy', $station) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this station?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 13px;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-secondary);">No radio stations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    {{ $stations->links() }}
@endsection
