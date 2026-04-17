@extends('layouts.app')
@section('title','New Invoice')
@section('content')

<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">Create Invoice</h5>
</div>

<div class="card">
<div class="card-body">
@if($rooms->isEmpty())
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        No occupied rooms. <a href="{{ route('tenants.create') }}">Add a tenant first.</a>
    </div>
@else
<form method="POST" action="{{ route('invoices.store') }}">
@csrf

<div class="mb-3">
    <label class="form-label fw-semibold small">Room & Tenant</label>
    <select name="room_id" class="form-select" id="roomSelect" required>
        <option value="">-- Select Room --</option>
        @foreach($rooms as $room)
        <option value="{{ $room->id }}"
            data-tenant-id="{{ $room->activeTenant?->id }}"
            data-tenant-name="{{ $room->activeTenant?->name }}"
            data-water-mode="{{ $room->water_mode }}"
            data-water-fixed="{{ $room->water_fixed_fee }}">
            {{ $room->name }} — {{ $room->activeTenant?->name ?? 'No Tenant' }}
        </option>
        @endforeach
    </select>
</div>
<input type="hidden" name="tenant_id" id="tenantId">

<div class="row g-2 mb-3">
    <div class="col-7">
        <label class="form-label fw-semibold small">Billing Month</label>
        <input type="month" name="month" class="form-control" value="{{ date('Y-m') }}" required>
    </div>
    <div class="col-5">
        <label class="form-label fw-semibold small">Rate (៛/$1)</label>
        <input type="number" name="exchange_rate" class="form-control" value="4100" required>
    </div>
</div>

{{-- Water: metered --}}
<div id="waterMetered" class="rounded-3 p-3 mb-3" style="background:#f0f9ff; border:1px solid #bae6fd">
    <div class="fw-semibold small mb-2">💧 Water Meter (m³)</div>
    <div class="row g-2">
        <div class="col-5">
            <label class="form-label" style="font-size:.72rem">Previous</label>
            <input type="number" step="0.01" name="prev_water" class="form-control form-control-sm"
                   value="{{ $lastInvoice?->curr_water ?? 0 }}">
        </div>
        <div class="col-5">
            <label class="form-label" style="font-size:.72rem">Current</label>
            <input type="number" step="0.01" name="curr_water" id="currWater" class="form-control form-control-sm">
        </div>
        <div class="col-2 d-flex align-items-end">
            <div class="text-info fw-bold small text-center w-100" id="waterUsed">0</div>
        </div>
    </div>
</div>

{{-- Water: fixed --}}
<div id="waterFixed" class="rounded-3 p-3 mb-3" style="display:none; background:#f0fdf4; border:1px solid #bbf7d0">
    <div class="fw-semibold small mb-1">💧 Water — Fixed Fee</div>
    <div class="text-muted small" id="fixedWaterNote">Flat fee applied automatically.</div>
</div>

{{-- Electric --}}
<div class="rounded-3 p-3 mb-3" style="background:#fffbeb; border:1px solid #fde68a">
    <div class="fw-semibold small mb-2">⚡ Electric Meter (kWh)</div>
    <div class="row g-2">
        <div class="col-5">
            <label class="form-label" style="font-size:.72rem">Previous</label>
            <input type="number" step="0.01" name="prev_electric" class="form-control form-control-sm"
                   value="{{ $lastInvoice?->curr_electric ?? 0 }}" required>
        </div>
        <div class="col-5">
            <label class="form-label" style="font-size:.72rem">Current</label>
            <input type="number" step="0.01" name="curr_electric" id="currElectric" class="form-control form-control-sm" required>
        </div>
        <div class="col-2 d-flex align-items-end">
            <div class="text-warning fw-bold small text-center w-100" id="electricUsed">0</div>
        </div>
    </div>
</div>

{{-- Extra --}}
<div class="row g-2 mb-4">
    <div class="col-5">
        <label class="form-label fw-semibold small">Extra Fee (USD)</label>
        <div class="input-group input-group-sm">
            <span class="input-group-text">$</span>
            <input type="number" step="0.01" name="extra_fee" class="form-control" value="0">
        </div>
    </div>
    <div class="col-7">
        <label class="form-label fw-semibold small">Note</label>
        <input type="text" name="extra_fee_note" class="form-control form-control-sm" placeholder="Trash, Internet...">
    </div>
</div>

<div class="d-grid gap-2">
    <button class="btn btn-primary">
        <i class="bi bi-file-earmark-check me-1"></i>Generate Invoice
    </button>
    <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>

</form>
@endif
</div>
</div>

<script>
document.getElementById('roomSelect').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    document.getElementById('tenantId').value = opt.dataset.tenantId || '';
    const isFixed = opt.dataset.waterMode === 'fixed';
    document.getElementById('waterMetered').style.display = isFixed ? 'none' : 'block';
    document.getElementById('waterFixed').style.display   = isFixed ? 'block' : 'none';
    if (isFixed) {
        document.getElementById('fixedWaterNote').textContent =
            'Flat fee: $' + parseFloat(opt.dataset.waterFixed || 0).toFixed(2) + '/month (auto-applied)';
    }
});

document.querySelectorAll('input[type=number]').forEach(el => el.addEventListener('input', function () {
    const pW = parseFloat(document.querySelector('[name=prev_water]').value) || 0;
    const cW = parseFloat(document.getElementById('currWater').value) || 0;
    const pE = parseFloat(document.querySelector('[name=prev_electric]').value) || 0;
    const cE = parseFloat(document.getElementById('currElectric').value) || 0;
    document.getElementById('waterUsed').textContent    = Math.max(0, cW - pW).toFixed(1) + 'm³';
    document.getElementById('electricUsed').textContent = Math.max(0, cE - pE).toFixed(1) + 'kWh';
}));
</script>
@endsection