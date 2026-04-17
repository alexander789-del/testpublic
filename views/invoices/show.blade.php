@extends('layouts.app')
@section('title','Invoice')
@section('content')

<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <a href="{{ route('invoices.print', $invoice) }}"
        target="_blank"
        class="btn btn-outline-dark btn-sm ms-auto">
            <i class="bi bi-printer me-1"></i>Print
    </a>
    <h5 class="fw-bold mb-0">Invoice #{{ $invoice->id }}</h5>
    <span class="badge ms-auto fs-6 bg-{{ $invoice->status==='paid'?'success':'danger' }}">
        {{ strtoupper($invoice->status) }}
    </span>
</div>

<div class="card mb-3">
    {{-- Header --}}
    <div class="card-header d-flex justify-content-between">
        <span class="fw-bold">{{ $invoice->month }}</span>
        <span class="text-muted small">{{ $invoice->room->name }}</span>
    </div>

    {{-- Tenant info --}}
    <div class="d-flex justify-content-between px-3 py-2 border-bottom" style="background:#f8fafc">
        <div>
            <div style="font-size:.7rem" class="text-muted">TENANT</div>
            <div class="fw-semibold small">{{ $invoice->tenant->name }}</div>
        </div>
        @if($invoice->tenant->phone)
        <div class="text-end">
            <div style="font-size:.7rem" class="text-muted">PHONE</div>
            <div class="small">{{ $invoice->tenant->phone }}</div>
        </div>
        @endif
    </div>

    <div class="card-body p-0">
        {{-- Row helper --}}
        @php
        $row = fn($label,$value,$sub=null) =>
            '<div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                <div>
                    <div class="small">'.$label.'</div>
                    '.($sub ? '<div class="text-muted" style="font-size:.72rem">'.$sub.'</div>' : '').'
                </div>
                <div class="fw-semibold small">'.$value.'</div>
             </div>';
        @endphp

        <div class="px-3 pt-2 pb-1">
            <span class="text-muted fw-semibold" style="font-size:.7rem;letter-spacing:.08em">🏠 MONTHLY RENT</span>
        </div>
        {!! $row('Room Fee', '$'.number_format($invoice->monthly_fee,2)) !!}

        <div class="px-3 pt-2 pb-1">
            <span class="text-muted fw-semibold" style="font-size:.7rem;letter-spacing:.08em">💧 WATER</span>
        </div>
        @if($invoice->water_mode === 'fixed')
            {!! $row('Fixed fee', '$'.number_format($invoice->water_fee_usd,2), 'Flat monthly rate') !!}
        @else
            {!! $row('Water usage',
                '$'.number_format($invoice->water_fee_usd,2),
                $invoice->water_used.' m³ × '.number_format($invoice->water_rate).'៛ = '.number_format($invoice->water_fee_riel).'៛'
            ) !!}
            {!! $row('Meter reading', $invoice->prev_water.' → '.$invoice->curr_water.' m³') !!}
        @endif

        <div class="px-3 pt-2 pb-1">
            <span class="text-muted fw-semibold" style="font-size:.7rem;letter-spacing:.08em">⚡ ELECTRIC</span>
        </div>
        {!! $row('Electric usage',
            '$'.number_format($invoice->electric_fee_usd,2),
            $invoice->electric_used.' kWh × '.number_format($invoice->electric_rate).'៛ = '.number_format($invoice->electric_fee_riel).'៛'
        ) !!}
        {!! $row('Meter reading', $invoice->prev_electric.' → '.$invoice->curr_electric.' kWh') !!}

        @if($invoice->extra_fee > 0)
        <div class="px-3 pt-2 pb-1">
            <span class="text-muted fw-semibold" style="font-size:.7rem;letter-spacing:.08em">➕ EXTRA</span>
        </div>
        {!! $row($invoice->extra_fee_note ?? 'Extra Fee', '$'.number_format($invoice->extra_fee,2)) !!}
        @endif

        {{-- Total --}}
        <div class="d-flex justify-content-between align-items-center px-3 py-3 mt-1" style="background:#f0fdf4; border-top: 2px solid #16a34a; border-radius:0 0 14px 14px">
            <span class="fw-bold fs-6">TOTAL</span>
            <span class="fw-bold fs-4 text-success">${{ number_format($invoice->total_usd,2) }}</span>
        </div>
    </div>
</div>

<div class="text-muted text-center mb-3" style="font-size:.75rem">
    Exchange rate: $1 = {{ number_format($invoice->exchange_rate) }}៛
</div>

@if($invoice->status === 'unpaid')
<form method="POST" action="{{ route('invoices.paid', $invoice) }}">
    @csrf @method('PATCH')
    <button class="btn btn-success w-100 btn-lg">
        <i class="bi bi-check-circle me-1"></i>Mark as Paid
    </button>
</form>
@else
<div class="alert alert-success text-center">
    <i class="bi bi-check-circle-fill me-2"></i>
    Paid on {{ $invoice->paid_date?->format('d M Y') }}
</div>
@endif
@endsection