@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
    <!-- ── Overview Stats Grid ───────────────────────────────────────────── -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 40px;">
        <div class="glass-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="color: var(--text-secondary); font-weight: 500; margin-bottom: 8px;">Total Stations</div>
                <div style="font-size: 36px; font-weight: 700; font-family: var(--font-heading);">{{ $totalStations }}</div>
            </div>
            <div style="width: 56px; height: 56px; border-radius: 16px; background: rgba(108, 99, 255, 0.15); color: var(--brand-primary); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <i class="fa-solid fa-tower-broadcast"></i>
            </div>
        </div>

        <div class="glass-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="color: var(--text-secondary); font-weight: 500; margin-bottom: 8px;">Active Stations</div>
                <div style="font-size: 36px; font-weight: 700; font-family: var(--font-heading); color: var(--success);">{{ $activeStations }}</div>
            </div>
            <div style="width: 56px; height: 56px; border-radius: 16px; background: rgba(16, 185, 129, 0.15); color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="glass-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="color: var(--text-secondary); font-weight: 500; margin-bottom: 8px;">Active Streams</div>
                <div style="font-size: 36px; font-weight: 700; font-family: var(--font-heading); color: var(--brand-secondary);">{{ $activeStreams }}</div>
            </div>
            <div style="width: 56px; height: 56px; border-radius: 16px; background: rgba(255, 101, 132, 0.15); color: var(--brand-secondary); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <i class="fa-solid fa-music"></i>
            </div>
        </div>

        <div class="glass-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="color: var(--text-secondary); font-weight: 500; margin-bottom: 8px;">Push Notifications</div>
                <div style="font-size: 36px; font-weight: 700; font-family: var(--font-heading); color: var(--warning);">{{ $totalNotifications }}</div>
            </div>
            <div style="width: 56px; height: 56px; border-radius: 16px; background: rgba(245, 158, 11, 0.15); color: var(--warning); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <i class="fa-solid fa-bell"></i>
            </div>
        </div>
    </div>

    <!-- ── Two Column Layout ─────────────────────────────────────────────── -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
        <!-- Left Column: Recent Stations -->
        <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <h2 style="font-size: 20px;">Recent Radio Stations</h2>
                <a href="{{ route('admin.stations.index') }}" class="btn btn-secondary" style="padding: 8px 16px; font-size: 13px;">View All</a>
            </div>

            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Station</th>
                            <th>Band / Freq</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentStations as $station)
                            <tr>
                                <td style="display: flex; align-items: center; gap: 16px;">
                                    @if ($station->logo_url)
                                        <img src="{{ $station->logo_url }}" alt="{{ $station->name }}" style="width: 40px; height: 40px; border-radius: 10px; object-fit: cover;">
                                    @else
                                        <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--brand-primary);">
                                            {{ substr($station->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 600;">{{ $station->name }}</div>
                                        <div style="font-size: 13px; color: var(--text-secondary);">{{ $station->tagline ?? 'No tagline' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 500;">{{ $station->band }}</div>
                                    <div style="font-size: 13px; color: var(--text-secondary);">{{ $station->frequency ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    @if ($station->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-warning">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.stations.edit', $station) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 13px;">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 32px; color: var(--text-secondary);">No radio stations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column: Recent Notifications -->
        <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <h2 style="font-size: 20px;">Recent Notifications</h2>
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary" style="padding: 8px 16px; font-size: 13px;">View All</a>
            </div>

            <div class="glass-card" style="display: flex; flex-direction: column; gap: 16px;">
                @forelse ($recentNotifications as $notification)
                    <div style="padding-bottom: 16px; border-bottom: 1px solid var(--border-glass); last-child: { border: none; padding: 0; }">
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 8px;">
                            <div style="font-weight: 600;">{{ $notification->title }}</div>
                            @if ($notification->status === 'sent')
                                <span class="badge badge-success" style="font-size: 10px;">Sent</span>
                            @elseif ($notification->status === 'draft')
                                <span class="badge badge-secondary" style="font-size: 10px;">Draft</span>
                            @else
                                <span class="badge badge-warning" style="font-size: 10px;">{{ ucfirst($notification->status) }}</span>
                            @endif
                        </div>
                        <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 8px;">{{ $notification->body }}</p>
                        <div style="font-size: 11px; color: var(--text-muted);">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 24px; color: var(--text-secondary);">No recent notifications.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
