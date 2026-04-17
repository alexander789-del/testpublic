@extends('layouts.app')
@section('title','Dashboard')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Dashboard</h5>
    <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i>New Invoice
    </a>
</div>

{{-- Stats --}}
<div class="row g-2 mb-3">
    <div class="col-6">
        <div class="stat-card" style="background:linear-gradient(135deg,#2563eb,#1d4ed8)">
            <div class="stat-value">${{ number_format($totalCollected,2) }}</div>
            <div class="stat-label"><i class="bi bi-cash-stack me-1"></i>Collected</div>
        </div>
    </div>
    <div class="col-6">
        <div class="stat-card" style="background:linear-gradient(135deg,#dc2626,#b91c1c)">
            <div class="stat-value">${{ number_format($totalPending,2) }}</div>
            <div class="stat-label"><i class="bi bi-hourglass-split me-1"></i>Pending</div>
        </div>
    </div>
    <div class="col-6">
        <div class="stat-card" style="background:linear-gradient(135deg,#16a34a,#15803d)">
            <div class="stat-value">{{ $paidInvoices }}</div>
            <div class="stat-label"><i class="bi bi-check-circle me-1"></i>Paid</div>
        </div>
    </div>
    <div class="col-6">
        <div class="stat-card" style="background:linear-gradient(135deg,#d97706,#b45309)">
            <div class="stat-value">{{ $unpaidInvoices }}</div>
            <div class="stat-label"><i class="bi bi-exclamation-circle me-1"></i>Unpaid</div>
        </div>
    </div>
</div>

{{-- Room status --}}
@if($room)
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-house-door me-2"></i>{{ $room->name }}</span>
        <span class="badge bg-{{ $room->status === 'occupied' ? 'success' : 'secondary' }}">
            {{ ucfirst($room->status) }}
        </span>
    </div>
    <div class="card-body">
        <div class="row g-2 text-center">
            <div class="col-4">
                <div class="text-muted" style="font-size:.72rem">TENANT FULL NAME</div>
                <div class="fw-semibold small">{{ $room->activeTenant?->name ?? '—' }}</div>
            </div>
            <div class="col-4">
                <div class="text-muted" style="font-size:.72rem">RENT FEE</div>
                <div class="fw-semibold small text-primary">${{ number_format($room->monthly_fee,2) }}</div>
            </div>
            <div class="col-4">
                <div class="text-muted" style="font-size:.72rem">WATER FEE</div>
                <div class="fw-semibold small">
                    @if($room->water_mode === 'fixed')
                        ${{ number_format($room->water_fixed_fee,2) }}/mo
                    @else
                        {{ number_format($room->water_rate) }}៛
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Recent invoices --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-receipt me-2"></i>Recent Invoices</span>
        <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-outline-secondary">All</a>
    </div>

    {{-- Mobile: card list --}}
    <div class="d-block d-md-none">
        @forelse($recentInvoices as $inv)
        <a href="{{ route('invoices.show',$inv) }}" class="text-decoration-none text-dark">
            <div class="d-flex align-items-center justify-content-between px-3 py-3 border-bottom">
                <div>
                    <div class="fw-semibold small">{{ $inv->month }}</div>
                    <div class="text-muted" style="font-size:.78rem">{{ $inv->tenant->name }}</div>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-success small">${{ number_format($inv->total_usd,2) }}</div>
                    <span class="badge bg-{{ $inv->status==='paid'?'success':'danger' }}">{{ ucfirst($inv->status) }}</span>
                </div>
            </div>
        </a>
        @empty
        <div class="text-center text-muted py-4">
            <i class="bi bi-inbox d-block fs-3 mb-2"></i>No invoices yet
        </div>
        @endforelse
    </div>

    {{-- Desktop: table --}}
    <div class="d-none d-md-block table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Month</th><th>Tenant</th><th>Total</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($recentInvoices as $inv)
                <tr>
                    <td class="fw-semibold">{{ $inv->month }}</td>
                    <td>{{ $inv->tenant->name }}</td>
                    <td class="fw-bold text-success">${{ number_format($inv->total_usd,2) }}</td>
                    <td><span class="badge bg-{{ $inv->status==='paid'?'success':'danger' }}">{{ ucfirst($inv->status) }}</span></td>
                    <td><a href="{{ route('invoices.show',$inv) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No invoices yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection