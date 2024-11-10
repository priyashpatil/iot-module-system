@extends('layouts.app')

@section('title', $module->name)

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('modules.show', $module->id) }}">{{ $module->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Failures</li>
        </ol>
    </nav>

    <div class="d-md-flex justify-content-between align-items-center mb-2 gap-2">
        <div class="mb-2 md-md-0">
            <h1 class="mb-0">Module Failures: {{ $module->name }}</h1>
            <p class="mb-0 text-muted">{{ $module->description }}</p>
        </div>
    </div>

    {{ $failures->links() }}

    @if ($failures->count() > 0)
        <div class="list-group mb-3">
            @foreach ($failures as $failure)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Error Code: {{ $failure->error_code }}</strong>
                            <span class="ms-2">{{ $failure->description }}</span>
                        </div>
                        <small class="text-muted">{{ $failure->failure_at }}</small>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-3 text-center text-muted">
            No failures have been recorded for this module.
        </div>
    @endif

    {{ $failures->links() }}
@endsection
