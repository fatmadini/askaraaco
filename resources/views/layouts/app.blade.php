<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TicketWave - @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg: #120d14; --surface: #15151f; --surface2: #1c1c2b;
            --border: #2a2a3d; --accent: #7c3aed; --accent2: #a855f7;
            --gold: #f59e0b; --text: #e8e8f0; --muted: #6b6b8a;
            --success: #10b981; --danger: #ef4444; --sidebar-w: 260px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

        .sidebar { width: var(--sidebar-w); background: var(--surface); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh; z-index: 100; overflow-y: auto; }
        .sidebar-brand { padding: 24px 20px 20px; border-bottom: 1px solid var(--border); }
        .sidebar-brand h2 { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 800; background: linear-gradient(135deg, #a855f7, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .sidebar-brand p { color: var(--muted); font-size: 11px; margin-top: 2px; }
        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .nav-section { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); padding: 12px 8px 6px; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; font-size: 14px; color: var(--muted); transition: all 0.15s; margin-bottom: 2px; text-decoration: none; }
        .nav-item:hover { background: var(--surface2); color: var(--text); }
        .nav-item.active { background: rgba(124,58,237,0.15); color: var(--accent2); }
        .nav-item .icon { font-size: 18px; width: 24px; text-align: center; }
        .sidebar-footer { padding: 16px 12px; border-top: 1px solid var(--border); }
        .user-info { display: flex; align-items: center; gap: 10px; padding: 10px; background: var(--surface2); border-radius: 10px; margin-bottom: 10px; }
        .avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--accent), var(--gold)); display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; color: white; }
        .user-name { font-size: 13px; font-weight: 500; }
        .user-role { font-size: 11px; color: var(--muted); }
        .btn-logout { width: 100%; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: var(--danger); border-radius: 8px; padding: 9px; font-size: 13px; cursor: pointer; transition: background 0.2s; text-decoration: none; display: block; text-align: center; }
        .btn-logout:hover { background: rgba(239,68,68,0.2); }

        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar { background: var(--surface); border-bottom: 1px solid var(--border); padding: 16px 28px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .topbar h3 { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 700; }
        .content { padding: 28px; flex: 1; }

        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; margin-bottom: 20px; }
        .card-header { padding: 18px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
        .card-header h4 { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; }
        .card-body { padding: 20px; }

        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--border); }
        th { background: var(--surface2); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); }
        tr:hover td { background: rgba(255,255,255,0.02); }

        .btn { padding: 8px 16px; border-radius: 8px; font-size: 13px; cursor: pointer; border: none; font-weight: 500; transition: all 0.15s; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-accent { background: var(--accent); color: white; }
        .btn-accent:hover { background: var(--accent2); }
        .btn-danger { background: rgba(239,68,68,0.15); color: var(--danger); border: 1px solid rgba(239,68,68,0.25); }
        .btn-outline { background: transparent; color: var(--text); border: 1px solid var(--border); }
        .btn-outline:hover { background: var(--surface2); }
        .btn-success { background: rgba(16,185,129,0.15); color: var(--success); border: 1px solid rgba(16,185,129,0.25); }
        .btn-gold { background: rgba(245,158,11,0.2); color: var(--gold); border: 1px solid rgba(245,158,11,0.3); }
        .btn-pdf { background: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }

        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; color: var(--muted); margin-bottom: 6px; font-weight: 500; }
        .form-control { width: 100%; background: var(--surface2); border: 1px solid var(--border); border-radius: 10px; padding: 11px 14px; color: var(--text); font-size: 14px; outline: none; }
        .form-control:focus { border-color: var(--accent); }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; }
        .badge-vip { background: rgba(245,158,11,0.15); color: var(--gold); }
        .badge-regular { background: rgba(124,58,237,0.15); color: var(--accent2); }
        .badge-economy { background: rgba(16,185,129,0.15); color: var(--success); }

        .price { color: var(--gold); font-weight: 600; }
        .empty-state { text-align: center; padding: 48px; color: var(--muted); }
        .foto-thumb { width: 44px; height: 44px; border-radius: 8px; background: var(--surface2); display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .foto-thumb img { width: 100%; height: 100%; object-fit: cover; }

        .alert { padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 16px; }
        .alert-success { background: rgba(16,185,129,0.15); color: var(--success); border: 1px solid rgba(16,185,129,0.3); }
        .alert-danger { background: rgba(239,68,68,0.15); color: var(--danger); border: 1px solid rgba(239,68,68,0.3); }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <h2>🎵 TicketWave</h2>
        <p>Manajemen Tiket Konser</p>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Utama</div>
        <a href="{{ url('/dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <span class="icon">🏠</span> Dashboard
        </a>

        @if(auth()->user()->isAdmin())
            <div class="nav-section">Manajemen</div>
            <a href="{{ url('/konser') }}" class="nav-item {{ request()->is('konser*') ? 'active' : '' }}">
                <span class="icon">🎤</span> Data Konser
            </a>
            <a href="{{ url('/tiket') }}" class="nav-item {{ request()->is('tiket*') ? 'active' : '' }}">
                <span class="icon">🎫</span> Data Tiket
            </a>
            <a href="{{ url('/transaksi') }}" class="nav-item {{ request()->is('transaksi') ? 'active' : '' }}">
                <span class="icon">💳</span> Transaksi
            </a>
            <a href="{{ url('/transaksi/laporan') }}" class="nav-item {{ request()->is('transaksi/laporan') ? 'active' : '' }}">
                <span class="icon">📊</span> Laporan
            </a>

            <a href="{{ route('transaksi.laporan_per_kota') }}" class="nav-item">
    <span class="icon">🗺️</span> Laporan Per Kota
</a>

        @else
            <div class="nav-section">Menu</div>
            <a href="{{ url('/events') }}" class="nav-item {{ request()->is('events') ? 'active' : '' }}">
                <span class="icon">🎟️</span> Event
            </a>
            <a href="{{ url('/my-tickets') }}" class="nav-item {{ request()->is('my-tickets') ? 'active' : '' }}">
                <span class="icon">🎫</span> Tiket Saya
            </a>
            <a href="{{ url('/profile') }}" class="nav-item {{ request()->is('profile') ? 'active' : '' }}">
                <span class="icon">👤</span> Profil
            </a>
            <a href="{{ url('/help') }}" class="nav-item {{ request()->is('help') ? 'active' : '' }}">
                <span class="icon">❓</span> Bantuan
            </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name ?? 'User' }}</div>
                <div class="user-role">{{ auth()->user()->isAdmin() ? 'Admin' : 'Customer' }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">🚪 Logout</button>
        </form>
    </div>
</aside>

<main class="main">
    <div class="topbar">
        <h3>@yield('page-title', 'Dashboard')</h3>
        <span style="font-size:13px;color:var(--muted);">{{ now()->translatedFormat('l, d F Y') }}</span>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">❌ {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</main>

@stack('scripts')
</body>
</html>