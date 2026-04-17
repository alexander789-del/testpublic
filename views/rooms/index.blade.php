@extends('layouts.app')
@section('title','Rooms')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-house-door me-2"></i>Rooms</h5>
    <a href="{{ route('rooms.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i>Add Room
    </a>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show py-2">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@forelse($rooms as $room)
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">{{ $room->name }}</span>
        <span class="badge bg-{{ $room->status === 'occupied' ? 'success' : 'secondary' }}">
            {{ ucfirst($room->status) }}
        </span>
    </div>
    <div class="card-body">

        {{-- Rates --}}
        <div class="row g-2 text-center mb-3">
            <div class="col-4">
                <div class="text-muted" style="font-size:.7rem">RENT</div>
                <div class="fw-bold text-primary">${{ number_format($room->monthly_fee,2) }}</div>
            </div>
            <div class="col-4">
                <div class="text-muted" style="font-size:.7rem">WATER</div>
                @if($room->water_mode === 'fixed')
                    <div class="fw-bold text-info">
                        ${{ number_format($room->water_fixed_fee,2) }}
                        <span class="text-muted fw-normal" style="font-size:.7rem">/mo</span>
                    </div>
                @else
                    <div class="fw-bold text-info">
                        {{ number_format($room->water_rate) }}
                        <span class="text-muted fw-normal" style="font-size:.7rem">៛/m³</span>
                    </div>
                @endif
            </div>
            <div class="col-4">
                <div class="text-muted" style="font-size:.7rem">ELECTRIC</div>
                <div class="fw-bold text-warning">
                    {{ number_format($room->electric_rate) }}
                    <span class="text-muted fw-normal" style="font-size:.7rem">៛/kWh</span>
                </div>
            </div>
        </div>

        {{-- Tenant --}}
       @if($room->activeTenant)
        <div class="rounded-3 p-2 mb-3 d-flex align-items-center gap-3"
            style="background:#f0fdf4;border:1px solid #bbf7d0">

            {{-- Photo or initials --}}
            <x-tenant-avatar :tenant="$room->activeTenant" :size="46" />

            <div class="flex-grow-1">
                <div class="small fw-semibold">{{ $room->activeTenant->name }}</div>
                @if($room->activeTenant->phone)
                <div class="text-muted" style="font-size:.72rem">
                    <i class="bi bi-telephone me-1"></i>{{ $room->activeTenant->phone }}
                </div>
                @endif
                <div class="text-muted" style="font-size:.72rem">
                    <i class="bi bi-calendar3 me-1"></i>
                    Since {{ $room->activeTenant->move_in_date->format('d M Y') }}
                    &nbsp;·&nbsp; {{ $room->activeTenant->days_stayed }} days
                </div>
            </div>

            {{-- Doc badge --}}
            <div>
                <span class="badge rounded-pill"
                    style="background:{{ $room->activeTenant->hasDocument() ? '#eff6ff' : '#fef2f2' }};
                            color:{{ $room->activeTenant->hasDocument() ? '#1d4ed8' : '#991b1b' }};
                            font-weight:500;font-size:.68rem">
                    <i class="bi bi-{{ $room->activeTenant->hasDocument() ? 'file-earmark-check' : 'file-earmark-x' }}"></i>
                </span>
            </div>
        </div>
        @else
        <div class="rounded-3 p-2 mb-3 d-flex align-items-center gap-2"
            style="background:#f8fafc;border:1px solid #e2e8f0">
            <div style="width:42px;height:42px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi bi-person text-muted"></i>
            </div>
            <span class="text-muted small">Vacant — no tenant</span>
            <a href="{{ route('tenants.create') }}"
            class="ms-auto btn btn-outline-success"
            style="font-size:.72rem;padding:2px 10px;border-radius:20px">
                + Add
            </a>
        </div>
        @endif

        {{-- Actions --}}
        <div class="d-flex gap-2">
            <a href="{{ route('rooms.show', $room) }}"
            class="btn btn-outline-secondary btn-sm flex-grow-1">
                <i class="bi bi-clock-history me-1"></i>History
            </a>
            <a href="{{ route('rooms.edit', $room) }}"
            class="btn btn-outline-primary btn-sm flex-grow-1">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            @if($room->status === 'vacant')
            <form method="POST" action="{{ route('rooms.destroy', $room) }}"
                onsubmit="return confirm('Delete {{ $room->name }}?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
            @endif
        </div>

    </div>
</div>
@empty
<div class="card">
    <div class="text-center text-muted py-5">
        <i class="bi bi-house-door fs-2 d-block mb-2"></i>
        No rooms yet.
        <div class="mt-2">
            <a href="{{ route('rooms.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Add First Room
            </a>
        </div>
    </div>
</div>
@endforelse

@endsection