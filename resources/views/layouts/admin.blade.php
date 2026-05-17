<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') — Radio App</title>
    <!-- Custom Vanilla CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="admin-wrapper">
        <!-- ── Sidebar Navigation ────────────────────────────────────────────── -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fa-solid fa-radio"></i>
                </div>
                <div class="sidebar-title">Radio Admin</div>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.stations.index') }}" class="nav-item {{ request()->routeIs('admin.stations.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tower-broadcast"></i>
                    <span>Radio Stations</span>
                </a>
                <a href="{{ route('admin.streams.index') }}" class="nav-item {{ request()->routeIs('admin.streams.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-music"></i>
                    <span>Stream Configs</span>
                </a>
                <a href="{{ route('admin.notifications.index') }}" class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-bell"></i>
                    <span>Push Notifications</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i>
                    <span>App Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile" style="background:transparent;border:none;padding:0;">
                    <div class="user-avatar" style="width:32px;height:32px;font-size:13px;">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <span style="font-size:14px;font-weight:500;">{{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
                
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="background:transparent;border:none;color:var(--text-secondary);cursor:pointer;padding:8px;" title="Logout">
                        <i class="fa-solid fa-right-from-bracket" style="font-size:18px;"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ── Main Content Area ─────────────────────────────────────────────── -->
        <main class="admin-main">
            <header class="admin-topbar">
                <div class="topbar-left">
                    <h1 class="page-title">@yield('title', 'Dashboard')</h1>
                </div>
                <div class="topbar-right">
                    <div class="user-profile">
                        <div class="user-avatar">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                    </div>
                </div>
            </header>

            <div class="admin-content">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
