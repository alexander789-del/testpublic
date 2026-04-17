@extends('layouts.app')
@section('title','Tenants')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Tenants</h5>
    <a href="{{ route('tenants.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i>Add
    </a>
</div>

{{-- Active tenants --}}
@php $active = $tenants->where('is_active', true); @endphp
@if($active->count())
<div class="mb-2 text-muted small fw-semibold px-1">
    CURRENT TENANTS ({{ $active->count() }})
</div>
<div class="card mb-4">
    @foreach($active as $tenant)
    <div class="px-3 py-3 border-bottom">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <x-tenant-avatar :tenant="$tenant" :size="44" />
            <div class="flex-grow-1">
                
                <div class="fw-semibold">{{ $tenant->name }}</div>
                <div class="text-muted small">
                    <i class="bi bi-house-door me-1"></i>{{ $tenant->room->name }}
                    @if($tenant->phone)
                        &nbsp;·&nbsp;<i class="bi bi-telephone me-1"></i>{{ $tenant->phone }}
                    @endif
                </div>

                {{-- Check-in / Check-out row --}}
                <div class="mt-2 d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill" style="background:#dcfce7; color:#15803d; font-weight:500">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        IN: {{ $tenant->check_in }}
                    </span>
                    <span class="badge rounded-pill" style="background:#f1f5f9; color:#475569; font-weight:500">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ $tenant->days_stayed }} days
                    </span>
                </div>
                {{-- document status badge --}}
                <span class="badge rounded-pill"
                    style="background:{{ $tenant->hasDocument() ? '#eff6ff' : '#fef2f2' }};
                            color:{{ $tenant->hasDocument() ? '#1d4ed8' : '#991b1b' }};
                            font-weight:500">
                    <i class="bi bi-{{ $tenant->hasDocument() ? 'file-earmark-check' : 'file-earmark-x' }} me-1"></i>
                    {{ $tenant->hasDocument() ? 'Doc ✓' : 'No Doc' }}
                </span>
                @if($tenant->notes)
                <div class="text-muted mt-1" style="font-size:.75rem">
                    <i class="bi bi-chat-left-text me-1"></i>{{ $tenant->notes }}
                </div>
                @endif
            </div>

            <div class="d-flex flex-column gap-1 align-items-end">
                <a href="{{ route('tenants.show', $tenant) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('tenants.checkout', $tenant) }}"
                   class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Past tenants --}}
@php $past = $tenants->where('is_active', false); @endphp
@if($past->count())
<div class="mb-2 text-muted small fw-semibold px-1">
    PAST TENANTS ({{ $past->count() }})
</div>
<div class="card">
    @foreach($past as $tenant)
    <div class="px-3 py-3 border-bottom">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <div class="flex-grow-1">
                <div class="fw-semibold text-muted">{{ $tenant->name }}</div>
                <div class="text-muted small">
                    <i class="bi bi-house-door me-1"></i>{{ $tenant->room->name }}
                </div>
                <div class="mt-2 d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill" style="background:#dcfce7; color:#15803d; font-weight:500">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        IN: {{ $tenant->check_in }}
                    </span>
                    <span class="badge rounded-pill" style="background:#fee2e2; color:#b91c1c; font-weight:500">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        OUT: {{ $tenant->check_out }}
                    </span>
                    <span class="badge rounded-pill" style="background:#f1f5f9; color:#475569; font-weight:500">
                        {{ $tenant->days_stayed }} days
                    </span>
                </div>
            </div>
            <div class="d-flex flex-column gap-1 align-items-end">
                <a href="{{ route('tenants.show', $tenant) }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-eye"></i>
                </a>
                <form method="POST" action="{{ route('tenants.destroy', $tenant) }}"
                      onsubmit="return confirm('Delete {{ $tenant->name }}?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@if($tenants->isEmpty())
<div class="card">
    <div class="text-center text-muted py-5">
        <i class="bi bi-people fs-2 d-block mb-2"></i>No tenants yet
    </div>
</div>
@endif

@endsection