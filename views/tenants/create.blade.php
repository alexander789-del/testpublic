@extends('layouts.app')
@section('title','Add Tenant')
@section('content')

<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">Check In Tenant</h5>
</div>

<form method="POST"
      action="{{ route('tenants.store') }}"
      enctype="multipart/form-data">
@csrf

{{-- Room --}}
<div class="card mb-3">
    <div class="card-header">
        <i class="bi bi-house-door me-2"></i>Room Assignment
    </div>
    <div class="card-body">
        @if($rooms->isEmpty())
        <div class="alert alert-warning mb-0">
            <i class="bi bi-exclamation-triangle me-2"></i>No vacant rooms available.
        </div>
        @else
        <select name="room_id" class="form-select" required>
            <option value="">-- Select Room --</option>
            @foreach($rooms as $room)
            <option value="{{ $room->id }}">
                {{ $room->name }} — ${{ number_format($room->monthly_fee,2) }}/mo
            </option>
            @endforeach
        </select>
        @endif
    </div>
</div>

@if(!$rooms->isEmpty())

{{-- Photo --}}
<div class="card mb-3">
    <div class="card-header">
        <i class="bi bi-camera me-2"></i>Profile Photo
        <span class="text-muted fw-normal small ms-1">(optional)</span>
    </div>
    <div class="card-body">
        <div id="photoDropZone"
             class="rounded-3 p-3 text-center"
             style="border:2px dashed #cbd5e1;cursor:pointer;transition:all .2s"
             onclick="document.getElementById('photoInput').click()"
             ondragover="event.preventDefault();this.style.borderColor='#3b82f6';this.style.background='#eff6ff'"
             ondragleave="this.style.borderColor='#cbd5e1';this.style.background=''"
             ondrop="handlePhotoDrop(event)">

            <div id="photoDropContent">
                <i class="bi bi-person-bounding-box fs-3 text-muted d-block mb-1"></i>
                <div class="small text-muted">Click or drag photo here</div>
                <div class="text-muted" style="font-size:.72rem">JPG, PNG, WEBP · Max 3MB</div>
            </div>

            <div id="photoPreview" style="display:none">
                <img id="photoPreviewImg" src=""
                     style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid #e2e8f0;margin-bottom:6px">
                <div class="small fw-semibold text-success" id="photoFileName"></div>
                <button type="button"
                        class="btn btn-sm btn-outline-danger mt-1"
                        onclick="clearPhoto(event)">
                    <i class="bi bi-x me-1"></i>Remove
                </button>
            </div>
        </div>
        <input type="file" id="photoInput" name="photo"
               accept=".jpg,.jpeg,.png,.webp"
               style="display:none"
               onchange="handlePhotoSelect(this)">
        @error('photo')
        <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Personal Info --}}
<div class="card mb-3">
    <div class="card-header">
        <i class="bi bi-person me-2"></i>Personal Information
    </div>
    <div class="card-body">

        <div class="mb-3">
            <label class="form-label fw-semibold small">
                Full Name <span class="text-danger">*</span>
            </label>
            <input type="text" name="name" class="form-control"
                   placeholder="e.g. Dara Sok"
                   value="{{ old('name') }}" required>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label fw-semibold small">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control"
                       value="{{ old('date_of_birth') }}"
                       max="{{ date('Y-m-d') }}">
            </div>
            <div class="col-6">
                <label class="form-label fw-semibold small">Nationality</label>
                <input type="text" name="nationality" class="form-control"
                       placeholder="e.g. Cambodian"
                       value="{{ old('nationality') }}">
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-7">
                <label class="form-label fw-semibold small">Phone</label>
                <input type="text" name="phone" class="form-control"
                       placeholder="012 345 678"
                       value="{{ old('phone') }}">
            </div>
            <div class="col-5">
                <label class="form-label fw-semibold small">Country</label>
                <input type="text" name="country" class="form-control"
                       placeholder="e.g. Cambodia"
                       value="{{ old('country') }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold small">National ID / Passport No.</label>
            <input type="text" name="national_id" class="form-control"
                   placeholder="e.g. 123456789"
                   value="{{ old('national_id') }}">
        </div>

        <div class="mb-0">
            <label class="form-label fw-semibold small">Birth Location (Full Address)</label>
            <textarea name="birth_location" class="form-control" rows="2"
                      placeholder="Village, Sangkat, Khan, Province">{{ old('birth_location') }}</textarea>
        </div>

    </div>
</div>

{{-- Document Upload --}}
<div class="card mb-3">
    <div class="card-header">
        <i class="bi bi-file-earmark-person me-2"></i>ID / Document
        <span class="text-muted fw-normal small ms-1">(optional)</span>
    </div>
    <div class="card-body">

        {{-- Document type --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small">Document Type</label>
            <div class="d-flex flex-wrap gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio"
                           name="id_card_type" id="typeNationalId"
                           value="national_id"
                           {{ old('id_card_type','national_id') === 'national_id' ? 'checked' : '' }}>
                    <label class="form-check-label small" for="typeNationalId">
                        <i class="bi bi-person-badge me-1"></i>National ID
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio"
                           name="id_card_type" id="typePassport"
                           value="passport"
                           {{ old('id_card_type') === 'passport' ? 'checked' : '' }}>
                    <label class="form-check-label small" for="typePassport">
                        <i class="bi bi-journal-bookmark me-1"></i>Passport
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio"
                           name="id_card_type" id="typeOther"
                           value="other"
                           {{ old('id_card_type') === 'other' ? 'checked' : '' }}>
                    <label class="form-check-label small" for="typeOther">
                        <i class="bi bi-file-earmark me-1"></i>Other
                    </label>
                </div>
            </div>
        </div>

        {{-- Document drop zone --}}
        <div id="docDropZone"
             class="rounded-3 p-3 text-center"
             style="border:2px dashed #cbd5e1;cursor:pointer;transition:all .2s"
             onclick="document.getElementById('docInput').click()"
             ondragover="event.preventDefault();this.style.borderColor='#3b82f6';this.style.background='#eff6ff'"
             ondragleave="this.style.borderColor='#cbd5e1';this.style.background=''"
             ondrop="handleDocDrop(event)">

            <div id="docDropContent">
                <i class="bi bi-cloud-upload fs-3 text-muted d-block mb-1"></i>
                <div class="small text-muted">Click or drag document here</div>
                <div class="text-muted" style="font-size:.72rem">
                    JPG, PNG, WEBP, PDF · Max 5MB
                </div>
            </div>

            <div id="docPreview" style="display:none">
                {{-- Image preview --}}
                <img id="docPreviewImg" src=""
                     style="max-height:140px;max-width:100%;border-radius:8px;margin-bottom:6px;display:none">
                {{-- PDF preview --}}
                <div id="docPdfIcon" style="display:none;margin-bottom:6px">
                    <i class="bi bi-file-earmark-pdf text-danger" style="font-size:2.5rem"></i>
                </div>
                <div class="small fw-semibold text-success" id="docFileName"></div>
                <div class="text-muted" style="font-size:.72rem" id="docFileSize"></div>
                <button type="button"
                        class="btn btn-sm btn-outline-danger mt-1"
                        onclick="clearDoc(event)">
                    <i class="bi bi-x me-1"></i>Remove
                </button>
            </div>
        </div>

        <input type="file" id="docInput" name="document"
               accept=".jpg,.jpeg,.png,.webp,.pdf"
               style="display:none"
               onchange="handleDocSelect(this)">

        @error('document')
        <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror

    </div>
</div>

{{-- Check-in --}}
<div class="card mb-3">
    <div class="card-header" style="background:#f0fdf4">
        <i class="bi bi-box-arrow-in-right me-2 text-success"></i>
        <span class="text-success fw-semibold">Check-In Details</span>
    </div>
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-7">
                <label class="form-label fw-semibold small">
                    Move In Date <span class="text-danger">*</span>
                </label>
                <input type="date" name="move_in_date" class="form-control"
                       value="{{ old('move_in_date', date('Y-m-d')) }}" required>
            </div>
            <div class="col-5">
                <label class="form-label fw-semibold small">Time</label>
                <input type="time" name="check_in_time" class="form-control"
                       value="{{ old('check_in_time', date('H:i')) }}">
            </div>
        </div>
        <div>
            <label class="form-label fw-semibold small">
                Notes <span class="text-muted fw-normal">(optional)</span>
            </label>
            <textarea name="notes" class="form-control" rows="2"
                      placeholder="e.g. Deposit paid, 2 keys given...">{{ old('notes') }}</textarea>
        </div>
    </div>
</div>

<div class="d-grid gap-2 mb-4">
    <button class="btn btn-success btn-lg">
        <i class="bi bi-box-arrow-in-right me-1"></i>Check In Tenant
    </button>
    <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>

@endif
</form>

<script>
// ── Photo ──────────────────────────────────────
function handlePhotoSelect(input) {
    if (input.files && input.files[0]) showPhoto(input.files[0]);
}
function handlePhotoDrop(e) {
    e.preventDefault();
    resetDrop('photoDropZone');
    const file = e.dataTransfer.files[0];
    if (file) { setFile('photoInput', file); showPhoto(file); }
}
function showPhoto(file) {
    const reader = new FileReader();
    reader.onload = e => document.getElementById('photoPreviewImg').src = e.target.result;
    reader.readAsDataURL(file);
    document.getElementById('photoDropContent').style.display = 'none';
    document.getElementById('photoPreview').style.display     = 'block';
    document.getElementById('photoFileName').textContent      = file.name;
}
function clearPhoto(e) {
    e.stopPropagation();
    document.getElementById('photoInput').value               = '';
    document.getElementById('photoDropContent').style.display = 'block';
    document.getElementById('photoPreview').style.display     = 'none';
}

// ── Document ───────────────────────────────────
function handleDocSelect(input) {
    if (input.files && input.files[0]) showDoc(input.files[0]);
}
function handleDocDrop(e) {
    e.preventDefault();
    resetDrop('docDropZone');
    const file = e.dataTransfer.files[0];
    if (file) { setFile('docInput', file); showDoc(file); }
}
function showDoc(file) {
    const ext = file.name.split('.').pop().toLowerCase();
    document.getElementById('docDropContent').style.display = 'none';
    document.getElementById('docPreview').style.display     = 'block';
    document.getElementById('docFileName').textContent      = file.name;
    document.getElementById('docFileSize').textContent      = (file.size/1024).toFixed(1) + ' KB';

    if (['jpg','jpeg','png','webp'].includes(ext)) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('docPreviewImg').src          = e.target.result;
            document.getElementById('docPreviewImg').style.display = 'block';
        };
        reader.readAsDataURL(file);
        document.getElementById('docPdfIcon').style.display = 'none';
    } else {
        document.getElementById('docPreviewImg').style.display = 'none';
        document.getElementById('docPdfIcon').style.display    = 'block';
    }
}
function clearDoc(e) {
    e.stopPropagation();
    document.getElementById('docInput').value               = '';
    document.getElementById('docDropContent').style.display = 'block';
    document.getElementById('docPreview').style.display     = 'none';
    document.getElementById('docPreviewImg').style.display  = 'none';
    document.getElementById('docPdfIcon').style.display     = 'none';
}

// ── Helpers ────────────────────────────────────
function resetDrop(id) {
    document.getElementById(id).style.borderColor = '#cbd5e1';
    document.getElementById(id).style.background  = '';
}
function setFile(inputId, file) {
    const dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById(inputId).files = dt.files;
}
</script>

@endsection