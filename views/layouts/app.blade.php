<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>🏠 Room Rental — @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .card { border: none; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
        .card-header { border-radius: 14px 14px 0 0 !important; background: #fff; border-bottom: 1px solid #f0f0f0; font-weight: 600; padding: .85rem 1.25rem; }
        .table thead th { background: #1e293b; color: #fff; border: none; font-weight: 500; font-size: .85rem; }
        .badge { font-size: .78rem; padding: .38em .75em; border-radius: 20px; }
        .btn { border-radius: 9px; }
        .nav-link { color: rgba(255,255,255,.7) !important; padding: .5rem .75rem !important; border-radius: 8px; transition: background .15s; }
        .nav-link:hover, .nav-link.active { color: #fff !important; background: rgba(255,255,255,.12); }

        /* Bottom nav for mobile */
        .bottom-nav { display: none; }
        @media (max-width: 767px) {
            body { padding-bottom: 70px; }
            .bottom-nav {
                display: flex;
                position: fixed;
                bottom: 0; left: 0; right: 0;
                background: #1e293b;
                z-index: 1000;
                height: 62px;
                border-top: 1px solid rgba(255,255,255,.1);
            }
            .bottom-nav a {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                color: rgba(255,255,255,.55);
                text-decoration: none;
                font-size: .65rem;
                gap: 3px;
                transition: color .15s;
            }
            .bottom-nav a i { font-size: 1.25rem; }
            .bottom-nav a.active, .bottom-nav a:hover { color: #fff; }
            .top-navbar { display: none !important; }
        }

        /* Table responsive tweaks */
        .table-mobile td, .table-mobile th { vertical-align: middle; }
        @media (max-width: 575px) {
            .hide-mobile { display: none !important; }
            .card-body { padding: 1rem; }
            .container { padding-left: 12px; padding-right: 12px; }
        }

        /* Stat cards */
        .stat-card { border-radius: 14px; padding: 1.1rem 1.25rem; color: white; }
        .stat-card .stat-value { font-size: 1.6rem; font-weight: 700; line-height: 1.1; }
        .stat-card .stat-label { font-size: .78rem; opacity: .8; margin-top: 4px; }
    </style>
</head>
<body>

{{-- Desktop navbar (hidden on mobile) --}}
<nav class="navbar navbar-expand-md navbar-dark bg-dark px-3 py-2 top-navbar">
    <a class="navbar-brand fw-bold fs-6" href="{{ route('dashboard') }}">🏠 Room Rental</a>
    <div class="navbar-nav ms-auto flex-row gap-1">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-speedometer2 me-1"></i>Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}" href="{{ route('rooms.index') }}">
            <i class="bi bi-house-door me-1"></i>Room
        </a>
        <a class="nav-link {{ request()->routeIs('tenants.*') ? 'active' : '' }}" href="{{ route('tenants.index') }}">
            <i class="bi bi-people me-1"></i>Tenants
        </a>
        <a class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
            <i class="bi bi-receipt me-1"></i>Invoices
        </a>
    </div>
</nav>

{{-- Mobile bottom nav --}}
<nav class="bottom-nav">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>Dashboard
    </a>
    <a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.*') ? 'active' : '' }}">
        <i class="bi bi-house-door"></i>Room
    </a>
    <a href="{{ route('tenants.index') }}" class="{{ request()->routeIs('tenants.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i>Tenants
    </a>
    <a href="{{ route('invoices.index') }}" class="{{ request()->routeIs('invoices.*') ? 'active' : '' }}">
        <i class="bi bi-receipt"></i>Invoices
    </a>
</nav>

<div class="container py-3">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm py-2">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-3 py-2">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>