@props(['module'])

<form action="{{ route('modules.toggle', $module) }}" method="POST" class="d-inline" id="toggleForm">
    @csrf
    @method('PUT')
    <button type="button" class="btn btn-{{ $module->status->value === 'deactivated' ? 'success' : 'warning' }}"
        data-bs-toggle="modal" data-bs-target="#toggleModal">
        {{ $module->status->value === 'deactivated' ? 'Activate' : 'Deactivate' }} Module
    </button>

    <!-- Toggle Confirmation Modal -->
    <div class="modal fade" id="toggleModal" tabindex="-1" aria-labelledby="toggleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="toggleModalLabel">
                        Confirm {{ $module->status->value === 'deactivated' ? 'Activation' : 'Deactivation' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($module->status->value !== 'deactivated')
                        Are you sure you want to deactivate this module? This action may affect running
                        processes.
                    @else
                        Are you sure you want to activate this module?
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit"
                        class="btn btn-{{ $module->status->value === 'deactivated' ? 'success' : 'warning' }}">
                        Confirm {{ $module->status->value === 'deactivated' ? 'Activation' : 'Deactivation' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
