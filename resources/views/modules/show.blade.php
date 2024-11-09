@extends('layouts.app')

@section('title', $module->name . ' - IOT Modules System')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Module</li>
        </ol>
    </nav>

    <div class="d-md-flex justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h1 class="mb-0">{{ $module->name }}</h1>
            <p class="mb-0 text-muted">{{ $module->description }}</p>
        </div>

        <div class="flex-shrink-0"><button class="btn btn-primary" type="button" data-bs-toggle="modal"
                data-bs-target="#addSensorModal">Add Sensor</button></div>
    </div>

    {{-- Metric Cards --}}
    <div class="row g-2">
        <div class="col-md-4">
            <x-metric-card title="Status">
                <span class="badge text-uppercase {{ $module->statusBadgeClass() }}">{{ $module->status }}</span>
            </x-metric-card>
        </div>
        <div class="col-md-4">
            <x-metric-card title="Operating Time">
                @if ($module->active_since)
                    {{ $module->active_since->diff(now())->format('%dd %hh %im') }}
                @else
                    N/A
                @endif
            </x-metric-card>
        </div>
        <div class="col-md-4">
            <x-metric-card title="Metric Count">
                {{ $module->data_items_sent }}
            </x-metric-card>
        </div>
    </div>

    <hr>

    <div class="row g-2">
        @forelse ($module->sensors as $sensor)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-0">{{ $sensor->name }}</h5>
                        <p class="text-muted">{{ $sensor->description }}</p>
                        <p class="text-muted">{{ $sensor->unit }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <p class="mb-0">No sensors found</p>
                    </div>
                </div>
            </div>
        @endforelse

    </div>

    <x-modals.add-sensor :moduleId='$module->id' />
@endsection
