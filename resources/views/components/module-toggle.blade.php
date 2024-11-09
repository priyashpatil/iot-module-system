@props(['module'])

<form action="{{ route('modules.toggle', $module) }}" method="POST" class="d-inline" id="toggleForm">
    @csrf
    @method('PUT')
    <button type="button" class="btn btn-{{ $module->status->value === 'operational' ? 'warning' : 'success' }}"
        data-bs-toggle="modal" data-bs-target="#toggleModal">
        <x-icons.{{ $module->status->value === 'operational' ? 'pause' : 'play' }} size="18" />
        {{ $module->status->value === 'operational' ? 'Deactivate' : 'Activate' }} Module
    </button>

    <!-- Toggle Confirmation Modal -->
    <div class="modal fade" id="toggleModal" tabindex="-1" aria-labelledby="toggleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="toggleModalLabel">
                        Confirm {{ $module->status->value === 'operational' ? 'Deactivation' : 'Activation' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($module->status->value === 'operational')
                        Are you sure you want to deactivate this module? This action may affect running
                        processes.
                    @else
                        Are you sure you want to activate this module?
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit"
                        class="btn btn-{{ $module->status->value === 'operational' ? 'warning' : 'success' }}">
                        Confirm {{ $module->status->value === 'operational' ? 'Deactivation' : 'Activation' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
