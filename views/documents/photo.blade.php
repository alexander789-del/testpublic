@extends('layouts.app')
@section('title','Tenant Photo')
@section('content')

<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('tenants.show', $tenant) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">Tenant Photo</h5>
</div>

<div class="card">
<div class="card-body">

    {{-- Current photo --}}
    <div class="text-center mb-4">
        @if($tenant->hasPhoto())
        <div class="position-relative d-inline-block">
            <img src="{{ $tenant->photo_url }}"
                 alt="{{ $tenant->name }}"
                 style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid #e2e8f0">
            <form method="POST" action="{{ route('documents.photo.destroy', $tenant) }}"
                  onsubmit="return confirm('Remove photo?')"
                  style="position:absolute;top:0;right:0">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm rounded-circle"
                        style="width:28px;height:28px;padding:0;line-height:1">
                    <i class="bi bi-x" style="font-size:.8rem"></i>
                </button>
            </form>
        </div>
        <div class="fw-semibold mt-2">{{ $tenant->name }}</div>
        <div class="text-muted small">{{ $tenant->room->name }}</div>
        @else
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-white"
             style="width:100px;height:100px;background:#1e293b;font-size:2rem">
            {{ strtoupper(substr($tenant->name,0,2)) }}
        </div>
        <div class="fw-semibold mt-2">{{ $tenant->name }}</div>
        <div class="text-muted small">No photo yet</div>
        @endif
    </div>

    <form method="POST"
          action="{{ route('documents.photo.store', $tenant) }}"
          enctype="multipart/form-data">
    @csrf

    {{-- Drop zone --}}
    <div class="mb-4">
        <div id="dropZone"
             class="rounded-3 p-4 text-center"
             style="border:2px dashed #cbd5e1;cursor:pointer;transition:all .2s"
             onclick="document.getElementById('photoInput').click()"
             ondragover="event.preventDefault();this.style.borderColor='#3b82f6';this.style.background='#eff6ff'"
             ondragleave="this.style.borderColor='#cbd5e1';this.style.background=''"
             ondrop="handleDrop(event)">

            <div id="dropContent">
                <i class="bi bi-person-bounding-box fs-2 text-muted d-block mb-2"></i>
                <div class="fw-semibold small">Click or drag photo here</div>
                <div class="text-muted" style="font-size:.75rem;margin-top:4px">
                    JPG, PNG, WEBP &nbsp;·&nbsp; Max 3MB
                </div>
            </div>

            <div id="photoPreview" style="display:none;text-align:center">
                <img id="previewImg" src=""
                     style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid #e2e8f0;margin-bottom:8px">
                <div class="small fw-semibold text-success" id="fileName"></div>
                <div class="text-muted" style="font-size:.72rem" id="fileSize"></div>
                <button type="button"
                        class="btn btn-sm btn-outline-danger mt-2"
                        onclick="clearFile(event)">
                    <i class="bi bi-x me-1"></i>Remove
                </button>
            </div>
        </div>

        <input type="file" id="photoInput" name="photo"
               accept=".jpg,.jpeg,.png,.webp"
               style="display:none"
               onchange="handleFileSelect(this)">

        @error('photo')
        <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-grid gap-2">
        <button class="btn btn-primary" id="uploadBtn" disabled>
            <i class="bi bi-camera me-1"></i>
            {{ $tenant->hasPhoto() ? 'Replace Photo' : 'Upload Photo' }}
        </button>
        <a href="{{ route('tenants.show', $tenant) }}"
           class="btn btn-outline-secondary">Cancel</a>
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
        document.getElementById('photoInput').files = dt.files;
        showFile(file);
    }
}

function showFile(file) {
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
    };
    reader.readAsDataURL(file);
    document.getElementById('dropContent').style.display  = 'none';
    document.getElementById('photoPreview').style.display = 'block';
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = (file.size/1024).toFixed(1) + ' KB';
    document.getElementById('uploadBtn').disabled = false;
}

function clearFile(e) {
    e.stopPropagation();
    document.getElementById('photoInput').value           = '';
    document.getElementById('dropContent').style.display  = 'block';
    document.getElementById('photoPreview').style.display = 'none';
    document.getElementById('uploadBtn').disabled = true;
}
</script>
@endsection