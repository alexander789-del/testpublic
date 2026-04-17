@extends('layouts.app')
@section('title','Check Out')
@section('content')

<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">Check Out</h5>
</div>

{{-- Tenant summary --}}
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                 style="width:48px; height:48px; background:#1e293b; font-size:1.1rem; flex-shrink:0">
                {{ strtoupper(substr($tenant->name, 0, 2)) }}
            </div>
            <div>
                <div class="fw-bold">{{ $tenant->name }}</div>
                <div class="text-muted small">{{ $tenant->room->name }}</div>
                <div class="mt-1">
                    <span class="badge rounded-pill" style="background:#dcfce7; color:#15803d">
                        <i class="bi bi-box-arrow-in-right me-1"></i>IN: {{ $tenant->check_in }}
                    </span>
                </div>
            </div>
            <div class="ms-auto text-end">
                <div class="text-muted small">Stayed</div>
                <div class="fw-bold fs-5">{{ $tenant->days_stayed }}</div>
                <div class="text-muted small">days</div>
            </div>
        </div>
    </div>
</div>

{{-- Checkout form --}}
<div class="card">
<div class="card-header" style="background:#fff7ed; border-bottom:1px solid #fed7aa">
    <i class="bi bi-box-arrow-right me-2 text-warning"></i>
    <span class="fw-semibold">Check-Out Details</span>
</div>
<div class="card-body">
<form method="POST" action="{{ route('tenants.checkout.store', $tenant) }}">
@csrf

<div class="rounded-3 p-3 mb-3" style="background:#fff7ed; border:1px solid #fed7aa">
    <div class="fw-semibold small mb-2" style="color:#c2410c">
        <i class="bi bi-box-arrow-right me-1"></i>Check-Out
    </div>
    <div class="row g-2">
        <div class="col-7">
            <label class="form-label" style="font-size:.72rem">Date</label>
            <input type="date" name="move_out_date" class="form-control form-control-sm"
                   value="{{ old('move_out_date', date('Y-m-d')) }}"
                   min="{{ $tenant->move_in_date->format('Y-m-d') }}"
                   required>
        </div>
        <div class="col-5">
            <label class="form-label" style="font-size:.72rem">Time (optional)</label>
            <input type="time" name="check_out_time" class="form-control form-control-sm"
                   value="{{ old('check_out_time', date('H:i')) }}">
        </div>
    </div>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold small">Notes <span class="text-muted fw-normal">(optional)</span></label>
    <textarea name="notes" class="form-control" rows="2"
              placeholder="e.g. Keys returned, deposit refunded...">{{ old('notes', $tenant->notes) }}</textarea>
</div>

<div class="alert alert-warning py-2 small">
    <i class="bi bi-exclamation-triangle me-1"></i>
    This will mark <strong>{{ $tenant->name }}</strong> as moved out and set the room to vacant.
</div>

<div class="d-grid gap-2">
    <button class="btn btn-warning fw-semibold">
        <i class="bi bi-box-arrow-right me-1"></i>Confirm Check Out
    </button>
    <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>

</form>
</div>
</div>
@endsection