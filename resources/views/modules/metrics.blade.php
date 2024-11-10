@extends('layouts.app')

@section('title', $module->name)

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('modules.show', $module->id) }}">{{ $module->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Metrics</li>
        </ol>
    </nav>

    <div class="d-md-flex justify-content-between align-items-center mb-2 gap-2">
        <div class="mb-2 md-md-0">
            <h1 class="mb-0">Module Metrics: {{ $module->name }}</h1>
            <p class="mb-0 text-muted">{{ $module->description }}</p>
        </div>
    </div>

    <form method="GET" class="mb-3" style="max-width: 320px;">
        <label for="sensor-select" class="form-label">Select Sensor</label>
        <select name="sensor" id="sensor-select" class="form-select" onchange="this.form.submit()">
            <option value="" selected>All Sensors</option>
            @foreach ($sensors as $sensor)
                <option value="{{ $sensor->id }}" {{ request('sensor') == $sensor->id ? 'selected' : '' }}>
                    {{ $sensor->name }}
                </option>
            @endforeach
        </select>
    </form>

    <hr>

    {{ $readings->links() }}

    <div class="list-group mb-3">
        @foreach ($readings as $reading)
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $reading->sensor->name }}</strong>
                        <span class="ms-2">{{ $reading->value }} {{ $reading->sensor->unit }}</span>
                    </div>
                    <small class="text-muted">{{ $reading->recorded_at }}</small>
                </div>
            </div>
        @endforeach
    </div>

    {{ $readings->links() }}
@endsection
