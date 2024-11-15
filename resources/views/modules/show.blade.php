@extends('layouts.app')

@section('title', $module->name)

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Module</li>
        </ol>
    </nav>

    <div class="d-md-flex justify-content-between align-items-center mb-3 gap-2">
        <div class="mb-2 md-md-0">
            <h1 class="mb-0">{{ $module->name }}</h1>
            <p class="mb-0 text-muted">{{ $module->description }}</p>
        </div>

        <div class="flex-shrink-0">
            <x-module-toggle :module='$module' />

            <button class="btn btn-primary ms-1" type="button" data-bs-toggle="modal"
                data-bs-target="#addSensorModal"><x-icons.plus size="18" /> Add Sensor</button>
        </div>
    </div>

    {{-- Metric Cards --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <x-metric-card title="Status">
                <span id="refStatus"
                    class="badge text-uppercase {{ $module->statusBadgeClass() }}">{{ $module->status }}</span>
            </x-metric-card>
        </div>
        <div class="col-6 col-md-3">
            <x-metric-card title="Operating Time">
                <div id="refOperatingTime">
                    {{ $module->operatingTime() }}
                </div>
            </x-metric-card>
        </div>
        <div class="col-6 col-md-3">
            <x-metric-card title="Metric Count">
                <div id="refMetricCount">{{ $module->metric_count }}</div>
            </x-metric-card>
        </div>
        <div class="col-6 col-md-3">
            <x-metric-card title="Failure Count">
                <div id="refFailureCount">{{ $module->failure_count }}</div>
            </x-metric-card>
        </div>
    </div>

    {{-- Stats Charts --}}
    <div class="d-flex justify-content-between align-content-center mb-2">
        <div class="small text-muted text-uppercase fw-semibold">Stats <span class="text-capitalize"
                style="font-size: 12px;">(Showing Recent 60)</span> </div>
        <a href="{{ route('modules.metrics', $module->id) }}">View All Logs</a>
    </div>

    <div class="row g-3 mb-3" id="statsChartContainer" data-module-id={{ $module->id }}>
        @forelse ($module->sensors as $sensor)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">{{ $sensor->name }} <span class="fw-bold"
                                id="sensorCurrentValue-{{ $sensor->id }}">({{ $sensor->current_value ?? 'NA' }}
                                {{ $sensor->unit }})</span></div>
                        <canvas class="text-muted bg-light" id="sensorChart-{{ $sensor->id }}"
                            style="height: 200px; width:100%;"></canvas>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <p class="mb-0 text-muted">No sensors found</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Failure Logs --}}
    <div class="d-flex justify-content-between align-content-center mb-3">
        <div class="small text-muted text-uppercase fw-semibold">
            Failure Logs <span class="text-capitalize" style="font-size: 12px;">(Showing Recent 10)</span>
        </div>
        <a href="{{ route('modules.failures', $module->id) }}">View All Logs</a>
    </div>
    <div class="list-group" id="failuresList">
        @forelse ($module->failures()->latest('failure_at')->limit(10)->get() as $failure)
            <div class="list-group-item d-flex justify-content-between">
                <div>
                    <h6 class="mb-0">{{ $failure->description }}</h6>
                    <small class="text-muted">Error Code: {{ $failure->error_code }}</small>
                </div>
                <small>{{ $failure->failure_at->diffForHumans() }}</small>
            </div>
        @empty
            <div class="list-group-item text-center">
                <p class="mb-0 text-muted">No failure logs found</p>
            </div>
        @endforelse
    </div>

    <x-modals.add-sensor :moduleId='$module->id' />
@endsection

@push('scripts')
    @vite(['resources/js/module-poll.js'])
@endpush
