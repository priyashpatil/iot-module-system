<div class="modal fade" id="addModuleModal" tabindex="-1" aria-labelledby="addModuleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addModuleModalLabel">Add Module</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('modules.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Module Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" placeholder="Enter module name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Module Description<span
                                class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="3" placeholder="Enter module description" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sensors<span class="text-danger">*</span></label>
                        <div id="sensors-container">
                            <div class="sensor-group mb-2">
                                <div class="row">
                                    <div class="col-5">
                                        <input type="text"
                                            class="form-control @error('sensors.0.name') is-invalid @enderror"
                                            name="sensors[0][name]" placeholder="Sensor name" required>
                                        @error('sensors.0.name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-5">
                                        <input type="text"
                                            class="form-control @error('sensors.0.unit') is-invalid @enderror"
                                            name="sensors[0][unit]" placeholder="Unit" required>
                                        @error('sensors.0.unit')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addSensor()">
                            Add Sensor
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Module</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let sensorCount = 1;

    function addSensor() {
        const container = document.getElementById('sensors-container');
        const newSensor = document.createElement('div');
        newSensor.classList.add('sensor-group', 'mb-2');
        newSensor.innerHTML = `
        <div class="row">
            <div class="col-5">
                <input type="text" class="form-control" name="sensors[${sensorCount}][name]"
                    placeholder="Sensor name" required>
            </div>
            <div class="col-5">
                <input type="text" class="form-control" name="sensors[${sensorCount}][unit]"
                    placeholder="Unit" required>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeSensor(this)">
                    <x-icons.trash size="18" />
                </button>
            </div>
        </div>
    `;
        container.appendChild(newSensor);
        sensorCount++;
    }

    function removeSensor(button) {
        button.closest('.sensor-group').remove();
    }
</script>
