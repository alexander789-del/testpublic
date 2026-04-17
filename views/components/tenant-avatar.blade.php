@props([
    'tenant',
    'size'   => 44,
    'radius' => '50%',
])

@if($tenant->hasPhoto())
    <img src="{{ $tenant->photo_url }}"
         alt="{{ $tenant->name }}"
         style="width:{{ $size }}px;height:{{ $size }}px;border-radius:{{ $radius }};object-fit:cover;flex-shrink:0;border:2px solid #e2e8f0">
@else
    <div style="width:{{ $size }}px;height:{{ $size }}px;border-radius:{{ $radius }};background:#1e293b;display:flex;align-items:center;justify-content:center;font-weight:700;color:white;font-size:{{ round($size * 0.35) }}px;flex-shrink:0">
        {{ strtoupper(substr($tenant->name,0,2)) }}
    </div>
@endif