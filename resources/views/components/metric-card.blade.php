@props(['title'])

<div class="card">
    <div class="card-body">
        <div class="card-subtitle mb-1 small text-muted">{{ $title }}</div>
        <div class="card-text">
            {{ $slot }}
        </div>
    </div>
</div>
