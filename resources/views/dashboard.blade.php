@extends('layouts.app')

@section('title', 'Welcome - IOT Modules System')

@section('content')
    <h1>Dashboard</h1>
    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Provident commodi est omnis debitis assumenda! Eveniet laudantium sed consectetur. Omnis vitae esse perferendis fugiat repellat? Et eveniet perspiciatis vero modi recusandae.</p>

    <div class="list-group mb-3">
        @foreach ($modules as $module)
            <a href="#" class="list-group-item list-group-item-action">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div>{{ $module->name }}</div>
                        <div class="text-muted small">{{ $module->description }}</div>
                    </div>
                    <span class="badge {{$module->statusBadgeClass()}}">{{$module->status}}</span>
                </div>
            </a>
        @endforeach
    </div>

    <div>
        {{$modules->links()}}
    </div>
@endsection
