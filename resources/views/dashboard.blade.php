@extends('layouts.app')

@section('title', 'Welcome - IOT Modules System')

@section('content')
    <div class="d-md-flex justify-content-between align-items-center mb-3 gap-2">
        <div class="mb-2 md-md-0">
            <h1 class="mb-0">Dashboard</h1>
            <p class="mb-0 text-muted">Lorem ipsum dolor sit, amet consectetur adipisicing elit.</p>
        </div>

        <div class="flex-shrink-0"><button class="btn btn-primary" type="button" data-bs-toggle="modal"
                data-bs-target="#addModuleModal">Add Module</button></div>
    </div>

    <div class="list-group mb-3">
        @foreach ($modules as $module)
            <a href="{{ route('modules.show', $module->id) }}" class="list-group-item list-group-item-action">
                <x-module-list-item :module="$module" />
            </a>
        @endforeach
    </div>

    <div>
        {{ $modules->links() }}
    </div>

    <x-modals.add-module />
@endsection
