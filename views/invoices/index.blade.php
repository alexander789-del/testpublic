@extends('layouts.app')
@section('title','Invoices')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-receipt me-2"></i>Invoices</h5>
    <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i>New
    </a>
</div>

<div class="card">
    {{-- Mobile card list --}}
    <div class="d-block d-md-none">
        @forelse($invoices as $inv)
        <a href="{{ route('invoices.show',$inv) }}" class="text-decoration-none text-dark">
            <div class="px-3 py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-semibold">{{ $inv->month }}</div>
                        <div class="text-muted small">{{ $inv->tenant->name }}</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-success">${{ number_format($inv->total_usd,2) }}</div>
                        <span class="badge bg-{{ $inv->status==='paid'?'success':'danger' }}">{{ ucfirst($inv->status) }}</span>
                    </div>
                </div>
                <div class="d-flex gap-3 mt-2" style="font-size:.75rem; color:#6b7280">
                    <span>🏠 ${{ number_format($inv->monthly_fee,2) }}</span>
                    <span>💧 ${{ number_format($inv->water_fee_usd,2) }}</span>
                    <span>⚡ ${{ number_format($inv->electric_fee_usd,2) }}</span>
                    @if($inv->extra_fee > 0)<span>➕ ${{ number_format($inv->extra_fee,2) }}</span>@endif
                </div>
            </div>
        </a>
        @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-receipt fs-2 d-block mb-2"></i>No invoices yet
        </div>
        @endforelse
    </div>

    {{-- Desktop table --}}
    <div class="d-none d-md-block table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr><th>#</th><th>Month</th><th>Tenant</th><th>Rent</th><th>Water</th><th>Electric</th><th>Extra</th><th>Total</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td class="text-muted small">{{ $inv->id }}</td>
                    <td class="fw-semibold">{{ $inv->month }}</td>
                    <td>{{ $inv->tenant->name }}</td>
                    <td>${{ number_format($inv->monthly_fee,2) }}</td>
                    <td>${{ number_format($inv->water_fee_usd,2) }}</td>
                    <td>${{ number_format($inv->electric_fee_usd,2) }}</td>
                    <td>${{ number_format($inv->extra_fee,2) }}</td>
                    <td class="fw-bold text-success">${{ number_format($inv->total_usd,2) }}</td>
                    <td><span class="badge bg-{{ $inv->status==='paid'?'success':'danger' }}">{{ ucfirst($inv->status) }}</span></td>
                    <td><a href="{{ route('invoices.show',$inv) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center text-muted py-4">No invoices yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection