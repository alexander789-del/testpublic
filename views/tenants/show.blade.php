@extends('layouts.app')
@section('title','Tenant Detail')
@section('content')

<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">Tenant Detail</h5>
</div>

{{-- Profile card --}}
<div class="card mb-3">
<div class="card-body">
    <div class="d-flex align-items-center gap-3 mb-3">
        <div class="position-relative">
            <x-tenant-avatar :tenant="$tenant" :size="58" />
            <a href="{{ route('documents.photo', $tenant) }}"
               class="position-absolute d-flex align-items-center justify-content-center"
               style="bottom:0;right:0;width:22px;height:22px;background:#1e293b;border-radius:50%;color:white;text-decoration:none">
                <i class="bi bi-camera-fill" style="font-size:.6rem"></i>
            </a>
        </div>
        <div class="flex-grow-1">
            <div class="fw-bold fs-6">{{ $tenant->name }}</div>
            <div class="text-muted small">{{ $tenant->room->name }}</div>
            @if($tenant->age)
            <div class="text-muted small">
                <i class="bi bi-cake2 me-1"></i>{{ $tenant->age }} years old
            </div>
            @endif
        </div>
        <div class="d-flex flex-column gap-1 align-items-end">
            <span class="badge bg-{{ $tenant->is_active ? 'success' : 'secondary' }}">
                {{ $tenant->is_active ? 'Active' : 'Moved Out' }}
            </span>
            <a href="{{ route('tenants.edit', $tenant) }}"
               class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
        </div>
    </div>

    {{-- Info rows --}}
    @php
    $rows = [
        ['Phone',         $tenant->phone          ?? '—', 'bi-telephone'],
        ['National ID',   $tenant->national_id    ?? '—', 'bi-person-badge'],
        ['Date of Birth', $tenant->date_of_birth
            ? $tenant->date_of_birth->format('d M Y') . ' (Age: ' . $tenant->age . ')'
            : '—',                                        'bi-calendar-date'],
        ['Nationality',   $tenant->nationality    ?? '—', 'bi-flag'],
        ['Country',       $tenant->country        ?? '—', 'bi-globe'],
        ['Birth Location',$tenant->birth_location ?? '—', 'bi-geo-alt'],
    ];
    @endphp

    @foreach($rows as [$label, $value, $icon])
    <div class="d-flex justify-content-between align-items-start py-2 border-bottom gap-3">
        <span class="text-muted small flex-shrink-0">
            <i class="bi {{ $icon }} me-2"></i>{{ $label }}
        </span>
        <span class="small fw-semibold text-end">{{ $value }}</span>
    </div>
    @endforeach

</div>
</div>

{{-- Timeline --}}
<div class="card mb-3">
    <div class="card-header"><i class="bi bi-clock-history me-2"></i>Stay Timeline</div>
    <div class="card-body p-0">

        {{-- Check in --}}
        <div class="d-flex gap-3 px-3 py-3 border-bottom align-items-start">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:36px; height:36px; background:#dcfce7">
                <i class="bi bi-box-arrow-in-right text-success"></i>
            </div>
            <div>
                <div class="fw-semibold small text-success">Checked In</div>
                <div class="small">{{ $tenant->move_in_date->format('d M Y') }}</div>
                @if($tenant->check_in_time)
                <div class="text-muted" style="font-size:.75rem">
                    <i class="bi bi-clock me-1"></i>
                    {{ \Carbon\Carbon::parse($tenant->check_in_time)->format('h:i A') }}
                </div>
                @endif
            </div>
        </div>

        {{-- Check out --}}
        <div class="d-flex gap-3 px-3 py-3 align-items-start">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:36px; height:36px; background:{{ $tenant->move_out_date ? '#fee2e2' : '#f1f5f9' }}">
                <i class="bi bi-box-arrow-right" style="color:{{ $tenant->move_out_date ? '#dc2626' : '#94a3b8' }}"></i>
            </div>
            <div>
                <div class="fw-semibold small" style="color:{{ $tenant->move_out_date ? '#dc2626' : '#94a3b8' }}">
                    {{ $tenant->move_out_date ? 'Checked Out' : 'Still Staying' }}
                </div>
                @if($tenant->move_out_date)
                <div class="small">{{ $tenant->move_out_date->format('d M Y') }}</div>
                @if($tenant->check_out_time)
                <div class="text-muted" style="font-size:.75rem">
                    <i class="bi bi-clock me-1"></i>
                    {{ \Carbon\Carbon::parse($tenant->check_out_time)->format('h:i A') }}
                </div>
                @endif
                @else
                <div class="text-muted small">{{ $tenant->days_stayed }} days so far</div>
                @endif
            </div>
            <div class="ms-auto text-end">
                <div class="fw-bold fs-5">{{ $tenant->days_stayed }}</div>
                <div class="text-muted" style="font-size:.72rem">DAYS</div>
            </div>
        </div>

    </div>
</div>

{{-- Notes --}}
@if($tenant->notes)
<div class="card mb-3">
    <div class="card-header"><i class="bi bi-chat-left-text me-2"></i>Notes</div>
    <div class="card-body small">{{ $tenant->notes }}</div>
</div>
@endif

{{-- Invoices --}}
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-receipt me-2"></i>Invoices</span>
        <span class="badge bg-secondary">{{ $tenant->invoices->count() }}</span>
    </div>
    @forelse($tenant->invoices->sortByDesc('month') as $inv)
    <a href="{{ route('invoices.show', $inv) }}" class="text-decoration-none text-dark">
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
            <div>
                <div class="small fw-semibold">{{ $inv->month }}</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="fw-bold text-success small">${{ number_format($inv->total_usd,2) }}</span>
                <span class="badge bg-{{ $inv->status==='paid'?'success':'danger' }}">{{ ucfirst($inv->status) }}</span>
            </div>
        </div>
    </a>
    @empty
    <div class="text-center text-muted py-3 small">No invoices yet</div>
    @endforelse
</div>
{{-- Document Card --}}
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-earmark-person me-2"></i>ID / Document</span>
        <a href="{{ route('documents.create', $tenant) }}"
           class="btn btn-sm btn-outline-primary">
            <i class="bi bi-{{ $tenant->hasDocument() ? 'arrow-repeat' : 'upload' }} me-1"></i>
            {{ $tenant->hasDocument() ? 'Replace' : 'Upload' }}
        </a>
    </div>

    @if($tenant->hasDocument())
    <div class="card-body p-0">

        {{-- Image preview --}}
        @if($tenant->isDocumentImage())
        <div class="text-center p-3 border-bottom">
            <a href="{{ route('documents.show', $tenant) }}" target="_blank">
                <img src="{{ $tenant->document_url }}"
                     alt="ID Document"
                     style="max-height:220px; max-width:100%; border-radius:10px; object-fit:cover">
            </a>
        </div>
        @elseif($tenant->isDocumentPdf())
        <div class="text-center p-4 border-bottom" style="background:#fef2f2">
            <i class="bi bi-file-earmark-pdf text-danger" style="font-size:3rem"></i>
            <div class="fw-semibold small mt-2">PDF Document</div>
        </div>
        @endif

        {{-- Document info --}}
        <div class="px-3 py-2 d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-semibold small">{{ $tenant->id_card_type_label }}</div>
                <div class="text-muted" style="font-size:.72rem">
                    {{ $tenant->id_card_original_name }}
                    &nbsp;·&nbsp;
                    Uploaded {{ $tenant->id_card_uploaded_at?->format('d M Y') }}
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('documents.show', $tenant) }}"
                   target="_blank"
                   class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i>
                </a>
                <form method="POST" action="{{ route('documents.destroy', $tenant) }}"
                      onsubmit="return confirm('Delete this document?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    @else
    <div class="card-body text-center py-4 text-muted">
        <i class="bi bi-file-earmark-person fs-2 d-block mb-2 opacity-25"></i>
        <div class="small">No document uploaded yet</div>
        <a href="{{ route('documents.create', $tenant) }}"
           class="btn btn-sm btn-primary mt-3">
            <i class="bi bi-upload me-1"></i>Upload Now
        </a>
    </div>
    @endif
</div>
{{-- Actions --}}
@if($tenant->is_active)
<a href="{{ route('tenants.checkout', $tenant) }}" class="btn btn-warning w-100 mb-2">
    <i class="bi bi-box-arrow-right me-1"></i>Check Out
</a>
@endif
<form method="POST" action="{{ route('tenants.destroy', $tenant) }}"
      onsubmit="return confirm('Delete {{ $tenant->name }} permanently?')">
    @csrf @method('DELETE')
    <button class="btn btn-outline-danger w-100">
        <i class="bi bi-trash me-1"></i>Delete Tenant
    </button>
</form>

@endsection