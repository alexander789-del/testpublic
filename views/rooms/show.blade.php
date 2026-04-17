@extends('layouts.app')
@section('title', $room->name . ' — History')
@section('content')

{{-- Header --}}
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">{{ $room->name }}</h5>
    <span class="badge ms-1 bg-{{ $room->status === 'occupied' ? 'success' : 'secondary' }}">
        {{ ucfirst($room->status) }}
    </span>
    <a href="{{ route('rooms.edit', $room) }}"
       class="btn btn-outline-primary btn-sm ms-auto">
        <i class="bi bi-pencil me-1"></i>Edit
    </a>
</div>

{{-- Room Rate Summary --}}
<div class="card mb-3">
    <div class="card-body">
        <div class="row g-2 text-center">
            <div class="col-4">
                <div class="text-muted" style="font-size:.7rem">RENT</div>
                <div class="fw-bold text-primary">${{ number_format($room->monthly_fee,2) }}</div>
            </div>
            <div class="col-4">
                <div class="text-muted" style="font-size:.7rem">WATER</div>
                @if($room->water_mode === 'fixed')
                    <div class="fw-bold text-info">${{ number_format($room->water_fixed_fee,2) }}<span class="text-muted fw-normal" style="font-size:.7rem">/mo</span></div>
                @else
                    <div class="fw-bold text-info">{{ number_format($room->water_rate) }}<span class="text-muted fw-normal" style="font-size:.7rem">៛/m³</span></div>
                @endif
            </div>
            <div class="col-4">
                <div class="text-muted" style="font-size:.7rem">ELECTRIC</div>
                <div class="fw-bold text-warning">{{ number_format($room->electric_rate) }}<span class="text-muted fw-normal" style="font-size:.7rem">៛/kWh</span></div>
            </div>
        </div>
    </div>
</div>

{{-- Stats Row --}}
<div class="row g-2 mb-3">
    <div class="col-6">
        <div class="card h-100" style="background:linear-gradient(135deg,#2563eb,#1d4ed8)">
            <div class="card-body text-white py-3">
                <div class="fs-4 fw-bold">${{ number_format($totalIncome,2) }}</div>
                <div style="font-size:.75rem; opacity:.8">
                    <i class="bi bi-cash-stack me-1"></i>Total Collected
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card h-100" style="background:linear-gradient(135deg,#dc2626,#b91c1c)">
            <div class="card-body text-white py-3">
                <div class="fs-4 fw-bold">${{ number_format($pendingIncome,2) }}</div>
                <div style="font-size:.75rem; opacity:.8">
                    <i class="bi bi-hourglass me-1"></i>Pending
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card h-100" style="background:linear-gradient(135deg,#0f766e,#0d9488)">
            <div class="card-body text-white py-3">
                <div class="fs-4 fw-bold">{{ $totalTenants }}</div>
                <div style="font-size:.75rem; opacity:.8">
                    <i class="bi bi-people me-1"></i>Total Tenants
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card h-100" style="background:linear-gradient(135deg,#7c3aed,#6d28d9)">
            <div class="card-body text-white py-3">
                <div class="fs-4 fw-bold">{{ $totalInvoices }}</div>
                <div style="font-size:.75rem; opacity:.8">
                    <i class="bi bi-receipt me-1"></i>Invoices
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Current Tenant --}}
@if($room->activeTenant)
<div class="mb-2 text-muted small fw-semibold px-1" style="letter-spacing:.05em">
    CURRENT TENANT
</div>
<div class="card mb-3" style="border-left:4px solid #16a34a; border-radius:0 14px 14px 0">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3">
            {{-- current tenant avatar --}}
            <x-tenant-avatar :tenant="$room->activeTenant" :size="46" />
            <div class="flex-grow-1">
                <div class="fw-bold">{{ $room->activeTenant->name }}</div>
                <div class="text-muted small">
                    @if($room->activeTenant->phone)
                        <i class="bi bi-telephone me-1"></i>{{ $room->activeTenant->phone }}
                    @endif
                </div>
                <div class="mt-1 d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill"
                          style="background:#dcfce7;color:#15803d;font-weight:500">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        IN: {{ $room->activeTenant->check_in }}
                    </span>
                    <span class="badge rounded-pill"
                          style="background:#f1f5f9;color:#475569;font-weight:500">
                        {{ $room->activeTenant->days_stayed }} days
                    </span>
                    <span class="badge rounded-pill"
                          style="background:{{ $room->activeTenant->hasDocument() ? '#eff6ff' : '#fef2f2' }};
                                 color:{{ $room->activeTenant->hasDocument() ? '#1d4ed8' : '#991b1b' }};
                                 font-weight:500">
                        <i class="bi bi-{{ $room->activeTenant->hasDocument() ? 'file-earmark-check' : 'file-earmark-x' }} me-1"></i>
                        {{ $room->activeTenant->hasDocument() ? 'Doc ✓' : 'No Doc' }}
                    </span>
                </div>
            </div>
            <div class="d-flex flex-column gap-1">
                <a href="{{ route('tenants.show', $room->activeTenant) }}"
                   class="btn btn-sm btn-outline-success">
                    <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('tenants.checkout', $room->activeTenant) }}"
                   class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@else
<div class="card mb-3">
    <div class="card-body text-center py-3">
        <i class="bi bi-house text-muted d-block fs-3 mb-2 opacity-25"></i>
        <div class="text-muted small">Room is currently vacant</div>
        <a href="{{ route('tenants.create') }}"
           class="btn btn-success btn-sm mt-2">
            <i class="bi bi-person-plus me-1"></i>Add Tenant
        </a>
    </div>
</div>
@endif

{{-- Tenant History --}}
@php
    $pastTenants = $room->tenants->where('is_active', false);
@endphp

<div class="mb-2 text-muted small fw-semibold px-1" style="letter-spacing:.05em">
    TENANT HISTORY ({{ $room->tenants->count() }})
</div>

<div class="card mb-3">
    @forelse($pastTenants as $tenant)
    <div class="px-3 py-3 border-bottom">
        <div class="d-flex align-items-start gap-3">

            {{-- past tenant avatars --}}
            <x-tenant-avatar :tenant="$tenant" :size="40" />

            <div class="flex-grow-1 min-width-0">
                <div class="fw-semibold">{{ $tenant->name }}</div>
                @if($tenant->phone)
                <div class="text-muted small">
                    <i class="bi bi-telephone me-1"></i>{{ $tenant->phone }}
                </div>
                @endif

                {{-- Timeline badges --}}
                <div class="mt-2 d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill"
                          style="background:#dcfce7;color:#15803d;font-weight:500;font-size:.7rem">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        {{ $tenant->move_in_date->format('d M Y') }}
                    </span>
                    <span class="badge rounded-pill"
                          style="background:#fee2e2;color:#b91c1c;font-weight:500;font-size:.7rem">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        {{ $tenant->move_out_date?->format('d M Y') ?? '—' }}
                    </span>
                    <span class="badge rounded-pill"
                          style="background:#f1f5f9;color:#475569;font-weight:500;font-size:.7rem">
                        {{ $tenant->days_stayed }} days
                    </span>
                </div>

                {{-- Invoice summary --}}
                @if($tenant->invoices->count())
                <div class="mt-2 d-flex flex-wrap gap-2">
                    <span style="font-size:.72rem; color:#64748b">
                        <i class="bi bi-receipt me-1"></i>
                        {{ $tenant->invoices->count() }} invoice(s)
                    </span>
                    <span style="font-size:.72rem; color:#16a34a; font-weight:600">
                        <i class="bi bi-cash me-1"></i>
                        ${{ number_format($tenant->invoices->where('status','paid')->sum('total_usd'),2) }} paid
                    </span>
                    @if($tenant->invoices->where('status','unpaid')->count())
                    <span style="font-size:.72rem; color:#dc2626; font-weight:600">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        ${{ number_format($tenant->invoices->where('status','unpaid')->sum('total_usd'),2) }} unpaid
                    </span>
                    @endif
                </div>
                @endif

                {{-- Notes --}}
                @if($tenant->notes)
                <div class="mt-1 text-muted" style="font-size:.72rem">
                    <i class="bi bi-chat-left-text me-1"></i>{{ $tenant->notes }}
                </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="d-flex flex-column gap-1 flex-shrink-0">
                <a href="{{ route('tenants.show', $tenant) }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-eye"></i>
                </a>
                @if($tenant->hasDocument())
                <a href="{{ route('documents.show', $tenant) }}"
                   target="_blank"
                   class="btn btn-sm btn-outline-info"
                   title="View Document">
                    <i class="bi bi-file-earmark-person"></i>
                </a>
                @endif
            </div>

        </div>
    </div>
    @empty
    <div class="text-center text-muted py-4">
        <i class="bi bi-clock-history d-block fs-3 mb-2 opacity-25"></i>
        <div class="small">No past tenants yet</div>
    </div>
    @endforelse
</div>

{{-- Recent Invoices --}}
<div class="mb-2 text-muted small fw-semibold px-1" style="letter-spacing:.05em">
    RECENT INVOICES
</div>
<div class="card mb-3">
    @forelse($room->invoices as $inv)
    <a href="{{ route('invoices.show', $inv) }}" class="text-decoration-none text-dark">
        <div class="d-flex align-items-center justify-content-between px-3 py-3 border-bottom">
            <div>
                <div class="fw-semibold small">{{ $inv->month }}</div>
                <div class="text-muted" style="font-size:.75rem">
                    {{ $inv->tenant->name }}
                </div>
            </div>
            <div class="text-end">
                <div class="fw-bold text-success small">
                    ${{ number_format($inv->total_usd,2) }}
                </div>
                <span class="badge bg-{{ $inv->status === 'paid' ? 'success' : 'danger' }}">
                    {{ ucfirst($inv->status) }}
                </span>
            </div>
        </div>
    </a>
    @empty
    <div class="text-center text-muted py-4 small">
        <i class="bi bi-receipt d-block fs-3 mb-2 opacity-25"></i>
        No invoices yet
    </div>
    @endforelse

    @if($totalInvoices > 5)
    <div class="text-center py-2">
        <a href="{{ route('invoices.index') }}"
           class="text-muted small text-decoration-none">
            View all {{ $totalInvoices }} invoices
            <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    @endif
</div>

@endsection