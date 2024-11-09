@if (session()->has('alert'))
    <div class="alert alert-{{ session('alert.type') }} alert-dismissible mb-2 fade show" role="alert">
        {{ session('alert.message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
