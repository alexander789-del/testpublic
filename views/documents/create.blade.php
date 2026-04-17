@extends('layouts.app')
@section('title','Upload Document')
@section('content')

<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('tenants.show', $tenant) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">Upload Document</h5>
</div>

{{-- Tenant info --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                 style="width:40px;height:40px;background:#1e293b;font-size:.95rem;flex-shrink:0">
                {{ strtoupper(substr($tenant->name,0,2)) }}
            </div>
            <div>
                <div class="fw-semibold">{{ $tenant->name }}</div>
                <div class="text-muted small">{{ $tenant->room->name }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
<div class="card-body">

    {{-- Current document --}}
    @if($tenant->hasDocument())
    <div class="rounded-3 p-3 mb-4 d-flex align-items-center gap-3"
         style="background:#fff7ed; border:1px solid #fed7aa">
        <i class="bi bi-file-earmark-check text-warning fs-4"></i>
        <div class="flex-grow-1">
            <div class="fw-semibold small">Current document</div>
            <div class="text-muted" style="font-size:.75rem">
                {{ $tenant->id_card_type_label }}
                &nbsp;·&nbsp; {{ $tenant->id_card_original_name }}
                &nbsp;·&nbsp; Uploaded {{ $tenant->id_card_uploaded_at->format('d M Y') }}
            </div>
        </div>
        <a href="{{ route('documents.show', $tenant) }}" target="_blank"
           class="btn btn-sm btn-outline-warning">
            <i class="bi bi-eye"></i>
        </a>
    </div>
    @endif

    <form method="POST"
          action="{{ route('documents.store', $tenant) }}"
          enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label fw-semibold small">Document Type</label>
        <div class="d-flex flex-wrap gap-3">
            <div class="form-check">
                <input class="form-check-input" type="radio"
                       name="id_card_type" id="typeNationalId"
                       value="national_id"
                       {{ old('id_card_type','national_id') === 'national_id' ? 'checked' : '' }}>
                <label class="form-check-label" for="typeNationalId">
                    <i class="bi bi-person-badge me-1"></i>National ID Card
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio"
                       name="id_card_type" id="typePassport"
                       value="passport"
                       {{ old('id_card_type') === 'passport' ? 'checked' : '' }}>
                <label class="form-check-label" for="typePassport">
                    <i class="bi bi-journal-bookmark me-1"></i>Passport
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio"
                       name="id_card_type" id="typeOther"
                       value="other"
                       {{ old('id_card_type') === 'other' ? 'checked' : '' }}>
                <label class="form-check-label" for="typeOther">
                    <i class="bi bi-file-earmark me-1"></i>Other
                </label>
            </div>
        </div>
    </div>

    {{-- Drop zone --}}
    <div class="mb-4">
        <label class="form-label fw-semibold small">Upload File</label>
        <div id="dropZone"
             class="rounded-3 p-4 text-center"
             style="border:2px dashed #cbd5e1; cursor:pointer; transition:all .2s"
             onclick="document.getElementById('fileInput').click()"
             ondragover="event.preventDefault(); this.style.borderColor='#3b82f6'; this.style.background='#eff6ff'"
             ondragleave="this.style.borderColor='#cbd5e1'; this.style.background=''"
             ondrop="handleDrop(event)">

            <div id="dropContent">
                <i class="bi bi-cloud-upload fs-2 text-muted d-block mb-2"></i>
                <div class="fw-semibold small">Drag & drop or click to upload</div>
                <div class="text-muted" style="font-size:.75rem; margin-top:4px">
                    JPG, PNG, WEBP, PDF &nbsp;·&nbsp; Max 5MB
                </div>
            </div>

            <div id="filePreview" style="display:none">
                <img id="previewImg" src="" alt=""
                     style="max-height:180px; max-width:100%; border-radius:8px; margin-bottom:8px">
                <div id="pdfPreview"
                     style="display:none; padding:20px; background:#f1f5f9; border-radius:8px; margin-bottom:8px">
                    <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                    <div class="fw-semibold small mt-2" id="pdfName"></div>
                </div>
                <div class="small fw-semibold text-success" id="fileName"></div>
                <div class="text-muted" style="font-size:.72rem" id="fileSize"></div>
                <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                        onclick="clearFile(event)">
                    <i class="bi bi-x me-1"></i>Remove
                </button>
            </div>
        </div>

        <input type="file" id="fileInput" name="document"
               accept=".jpg,.jpeg,.png,.webp,.pdf"
               style="display:none"
               onchange="handleFileSelect(this)">

        @error('document')
        <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-grid gap-2">
        <button class="btn btn-primary" id="uploadBtn" disabled>
            <i class="bi bi-cloud-upload me-1"></i>
            {{ $tenant->hasDocument() ? 'Replace Document' : 'Upload Document' }}
        </button>
        <a href="{{ route('tenants.show', $tenant) }}" class="btn btn-outline-secondary">
            Cancel
        </a>
    </div>

    </form>
</div>
</div>

<script>
function handleFileSelect(input) {
    if (input.files && input.files[0]) showFile(input.files[0]);
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('dropZone').style.borderColor = '#cbd5e1';
    document.getElementById('dropZone').style.background  = '';
    const file = e.dataTransfer.files[0];
    if (file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('fileInput').files = dt.files;
        showFile(file);
    }
}

function showFile(file) {
    const ext = file.name.split('.').pop().toLowerCase();
    document.getElementById('dropContent').style.display = 'none';
    document.getElementById('filePreview').style.display = 'block';
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
    document.getElementById('uploadBtn').disabled = false;

    if (['jpg','jpeg','png','webp'].includes(ext)) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewImg').style.display = 'block';
        };
        reader.readAsDataURL(file);
        document.getElementById('pdfPreview').style.display = 'none';
    } else {
        document.getElementById('previewImg').style.display = 'none';
        document.getElementById('pdfPreview').style.display  = 'block';
        document.getElementById('pdfName').textContent = file.name;
    }
}

function clearFile(e) {
    e.stopPropagation();
    document.getElementById('fileInput').value = '';
    document.getElementById('dropContent').style.display = 'block';
    document.getElementById('filePreview').style.display = 'none';
    document.getElementById('uploadBtn').disabled = true;
}
</script>
@endsection