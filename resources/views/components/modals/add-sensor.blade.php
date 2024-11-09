@props(['moduleId'])

<div class="modal fade" id="addSensorModal" tabindex="-1" aria-labelledby="addSensorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addSensorModalLabel">Add Sensor</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('module.sensors.store', $moduleId) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Sensor Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" placeholder="Enter sensor name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="unit" class="form-label">Unit<span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('unit') is-invalid @enderror" id="unit"
                            name="unit" placeholder="Enter sensor unit (e.g. °C, %, ppm)" value="{{ old('unit') }}"
                            required>
                        @error('unit')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Sensor</button>
                </div>
            </form>
        </div>
    </div>
</div>
