@extends('layouts.app')
@section('title','Add Room')
@section('content')

<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">Add New Room</h5>
</div>

<div class="card">
<div class="card-body">
<form method="POST" action="{{ route('rooms.store') }}">
@csrf

<div class="mb-3">
    <label class="form-label fw-semibold small">Room Name</label>
    <input type="text" name="name" class="form-control"
           placeholder="e.g. Room 2, Room A, Unit 3B"
           value="{{ old('name') }}" required>
    <div class="form-text">Must be unique.</div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold small">Monthly Rent (USD)</label>
    <div class="input-group">
        <span class="input-group-text">$</span>
        <input type="number" step="0.01" name="monthly_fee"
               class="form-control" value="{{ old('monthly_fee', '30') }}" required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold small">💧 Water Billing Mode</label>
    <div class="d-flex gap-4 mt-1">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="water_mode"
                   id="modeMetered" value="metered"
                   {{ old('water_mode','metered') === 'metered' ? 'checked' : '' }}
                   onchange="toggleWaterMode()">
            <label class="form-check-label small" for="modeMetered">
                📊 Metered (per m³)
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="water_mode"
                   id="modeFixed" value="fixed"
                   {{ old('water_mode') === 'fixed' ? 'checked' : '' }}
                   onchange="toggleWaterMode()">
            <label class="form-check-label small" for="modeFixed">
                📌 Fixed (flat/month)
            </label>
        </div>
    </div>
</div>

<div id="meteredSection" class="mb-3">
    <label class="form-label fw-semibold small">Water Rate (Riel per m³)</label>
    <div class="input-group">
        <input type="number" step="0.01" name="water_rate"
               class="form-control" value="{{ old('water_rate', '2500') }}">
        <span class="input-group-text">៛/m³</span>
    </div>
</div>

<div id="fixedSection" class="mb-3" style="display:none">
    <label class="form-label fw-semibold small">Fixed Water Fee (USD/month)</label>
    <div class="input-group">
        <span class="input-group-text">$</span>
        <input type="number" step="0.01" name="water_fixed_fee"
               class="form-control" value="{{ old('water_fixed_fee', '2') }}">
        <span class="input-group-text">/mo</span>
    </div>
    <div class="form-text">Flat fee regardless of usage.</div>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold small">⚡ Electric Rate (Riel per kWh)</label>
    <div class="input-group">
        <input type="number" step="0.01" name="electric_rate"
               class="form-control" value="{{ old('electric_rate', '700') }}" required>
        <span class="input-group-text">៛/kWh</span>
    </div>
</div>

<div class="d-grid gap-2">
    <button class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Room
    </button>
    <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>

</form>
</div>
</div>

<script>
function toggleWaterMode() {
    const isFixed = document.getElementById('modeFixed').checked;
    document.getElementById('meteredSection').style.display = isFixed ? 'none' : 'block';
    document.getElementById('fixedSection').style.display   = isFixed ? 'block' : 'none';
}
toggleWaterMode();
</script>
@endsection