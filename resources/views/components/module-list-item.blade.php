@props(['module'])

<div class="d-flex justify-content-between align-items-center gap-2">
    <div>
        <div>{{ $module->name }}</div>
        <div class="text-muted small">{{ $module->description }}</div>
    </div>
    <span class="badge text-uppercase {{ $module->statusBadgeClass() }}">{{ $module->status }}</span>
</div>
