@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <!-- Header section with title and back button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-truck"></i> Create Unit</h1>
            <a href="{{ route('admin.units.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back to Units
            </a>
        </div>

        <!-- Flash message for error notifications -->
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <!-- Form section -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <form action="{{ route('admin.units.store') }}" method="POST" id="createUnitForm">
                            @csrf
                            <div class="mb-3">
                                <label for="unit_code" class="form-label">Unit Code</label>
                                <input type="text" class="form-control @error('unit_code') is-invalid @enderror" 
                                    id="unit_code" name="unit_code" value="{{ old('unit_code') }}" required>
                                @error('unit_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="unit_type" class="form-label">Unit Type</label>
                                <input type="text" class="form-control @error('unit_type') is-invalid @enderror" 
                                    id="unit_type" name="unit_type" value="{{ old('unit_type') }}" required>
                                @error('unit_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                    id="latitude" name="latitude" value="{{ old('latitude') }}" required>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                    id="longitude" name="longitude" value="{{ old('longitude') }}" required>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="submitSpinner"></span>
                                    Create Unit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Map section -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div id="map" style="height: 600px; width: 100%; border-radius: 8px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet Map Integration -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Map Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map centered on Philippines
            const map = L.map('map').setView([14.5995, 120.9842], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let marker = null;

            // Update marker position when coordinates change
            function updateMarker() {
                const lat = document.getElementById('latitude').value;
                const lng = document.getElementById('longitude').value;

                if (lat && lng) {
                    if (marker) {
                        marker.setLatLng([lat, lng]);
                    } else {
                        marker = L.marker([lat, lng]).addTo(map);
                    }
                    map.setView([lat, lng], 15);
                }
            }

            // Handle map clicks
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                document.getElementById('latitude').value = lat.toFixed(7);
                document.getElementById('longitude').value = lng.toFixed(7);

                updateMarker();
            });

            // Update marker when coordinates are manually entered
            document.getElementById('latitude').addEventListener('change', updateMarker);
            document.getElementById('longitude').addEventListener('change', updateMarker);

            // Prevent double form submission
            const form = document.getElementById('createUnitForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitSpinner = document.getElementById('submitSpinner');

            form.addEventListener('submit', function(e) {
                if (submitBtn.disabled) {
                    e.preventDefault();
                    return;
                }

                submitBtn.disabled = true;
                submitSpinner.classList.remove('d-none');
                submitBtn.innerHTML = submitSpinner.outerHTML + ' Creating...';
            });
        });
    </script>
@endsection 