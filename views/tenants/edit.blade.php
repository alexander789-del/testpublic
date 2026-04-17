@extends('layouts.app')
@section('title','Edit Tenant')
@section('content')

<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('tenants.show', $tenant) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">Edit Tenant Info</h5>
</div>

{{-- Tenant header --}}
<div class="card mb-3">
<div class="card-body py-2">
    <div class="d-flex align-items-center gap-3">
        <x-tenant-avatar :tenant="$tenant" :size="44" />
        <div>
            <div class="fw-bold">{{ $tenant->name }}</div>
            <div class="text-muted small">{{ $tenant->room->name }}</div>
        </div>
        <span class="ms-auto badge bg-{{ $tenant->is_active ? 'success' : 'secondary' }}">
            {{ $tenant->is_active ? 'Active' : 'Moved Out' }}
        </span>
    </div>
</div>
</div>

<form method="POST" action="{{ route('tenants.update', $tenant) }}">
@csrf @method('PUT')

{{-- Personal Info --}}
<div class="card mb-3">
    <div class="card-header">
        <i class="bi bi-person me-2"></i>Personal Information
    </div>
    <div class="card-body">

        <div class="mb-3">
            <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $tenant->name) }}" required>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label fw-semibold small">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control"
                       value="{{ old('date_of_birth', $tenant->date_of_birth?->format('Y-m-d')) }}"
                       max="{{ date('Y-m-d') }}">
            </div>
            <div class="col-6">
                <label class="form-label fw-semibold small">Nationality</label>
                <input type="text" name="nationality" class="form-control"
                       placeholder="e.g. Cambodian"
                       value="{{ old('nationality', $tenant->nationality) }}">
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-7">
                <label class="form-label fw-semibold small">Phone</label>
                <input type="text" name="phone" class="form-control"
                       placeholder="012 345 678"
                       value="{{ old('phone', $tenant->phone) }}">
            </div>
            <div class="col-5">
                <label class="form-label fw-semibold small">Country</label>
                <input type="text" name="country" class="form-control"
                       placeholder="e.g. Cambodia"
                       value="{{ old('country', $tenant->country) }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold small">National ID / Passport No.</label>
            <input type="text" name="national_id" class="form-control"
                   value="{{ old('national_id', $tenant->national_id) }}">
        </div>

        <div class="mb-0">
            <label class="form-label fw-semibold small">Birth Location (Full Address)</label>
            <textarea name="birth_location" class="form-control" rows="2"
                      placeholder="Village, Sangkat, Khan, Province">{{ old('birth_location', $tenant->birth_location) }}</textarea>
        </div>

    </div>
</div>

{{-- Notes --}}
<div class="card mb-3">
    <div class="card-header">
        <i class="bi bi-chat-left-text me-2"></i>Notes
    </div>
    <div class="card-body">
        <textarea name="notes" class="form-control" rows="2"
                  placeholder="Any notes about this tenant...">{{ old('notes', $tenant->notes) }}</textarea>
    </div>
</div>

<div class="d-grid gap-2 mb-4">
    <button class="btn btn-primary">
        <i class="bi bi-save me-1"></i>Save Changes
    </button>
    <a href="{{ route('tenants.show', $tenant) }}" class="btn btn-outline-secondary">Cancel</a>
</div>

</form>
@endsection