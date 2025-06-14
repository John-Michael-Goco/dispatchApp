@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header">
                        <h2 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-plus-circle"></i> Create New Emergency</h2>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.emergencies.store') }}" method="POST" id="createEmergencyForm">
                            @csrf
                            <div class="mb-3">
                                <label for="incident" class="form-label">Incident Description</label>
                                <textarea class="form-control @error('incident') is-invalid @enderror" id="incident" 
                                    name="incident" rows="3" required>{{ old('incident') }}</textarea>
                                @error('incident')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="latitude" class="form-label">Latitude</label>
                                    <input type="text" class="form-control @error('latitude') is-invalid @enderror" 
                                        id="latitude" name="latitude" value="{{ old('latitude') }}" required>
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="longitude" class="form-label">Longitude</label>
                                    <input type="text" class="form-control @error('longitude') is-invalid @enderror" 
                                        id="longitude" name="longitude" value="{{ old('longitude') }}" required>
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Map Container -->
                            <div class="mb-3">
                                <label class="form-label">Select Location</label>
                                <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.emergencies.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to List
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="submitSpinner"></span>
                                    <i class="bi bi-save"></i> Create Emergency
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet Map Integration -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map centered on Philippines
            const map = L.map('map').setView([14.5995, 120.9842], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let marker;

            // Get current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Update input fields
                    document.getElementById('latitude').value = lat.toFixed(6);
                    document.getElementById('longitude').value = lng.toFixed(6);

                    // Update marker
                    if (marker) {
                        marker.setLatLng([lat, lng]);
                    } else {
                        marker = L.marker([lat, lng]).addTo(map);
                    }

                    // Center map on current location
                    map.setView([lat, lng], 15);
                }, function(error) {
                    console.error('Error getting location:', error);
                });
            } else {
                console.error('Geolocation is not supported by your browser');
            }

            // Update coordinates when clicking on the map
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                // Update input fields
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);

                // Update marker
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
            });

            // Update marker when coordinates are manually entered
            const updateMarker = () => {
                const lat = document.getElementById('latitude').value;
                const lng = document.getElementById('longitude').value;

                if (lat && lng) {
                    const latLng = [parseFloat(lat), parseFloat(lng)];
                    if (marker) {
                        marker.setLatLng(latLng);
                    } else {
                        marker = L.marker(latLng).addTo(map);
                    }
                    map.setView(latLng, 15);
                }
            };

            document.getElementById('latitude').addEventListener('change', updateMarker);
            document.getElementById('longitude').addEventListener('change', updateMarker);

            // Prevent double form submission
            const form = document.getElementById('createEmergencyForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitSpinner = document.getElementById('submitSpinner');

            form.addEventListener('submit', function(e) {
                if (submitBtn.disabled) {
                    e.preventDefault();
                    return;
                }

                submitBtn.disabled = true;
                submitSpinner.classList.remove('d-none');
                submitBtn.innerHTML = submitSpinner.outerHTML + ' Creating Emergency...';
            });
        });
    </script>
@endsection 