@extends('layouts.admin.app')

@section('content')
<!-- Main container for create branch form -->
<div class="container">
    <!-- Header section with title -->
    <h2 class="mb-4" style="font-size:1.5rem;"><i class="bi bi-plus-circle"></i> Create Branch</h2>
    <!-- Card container for the form -->
    <div class="card shadow mx-auto" style="max-width: 1100px;">
        <div class="card-body">
            <!-- Branch creation form -->
            <form action="{{ route('admin.branches.store') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Left column: Basic branch information -->
                    <div class="col-md-5">
                        <!-- Branch name input field -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name of Branch</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Service selection dropdown -->
                        <div class="mb-3">
                            <label for="service_id" class="form-label">Service</label>
                            <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id" required>
                                <option value="">Select Service</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Contact number input field -->
                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <div class="input-group">
                                <span class="input-group-text">+63</span>
                                <input type="number" 
                                    class="form-control @error('contact_number') is-invalid @enderror" 
                                    id="contact_number" 
                                    name="contact_number" 
                                    value="{{ old('contact_number') }}" 
                                    pattern="9\d{9}"
                                    maxlength="10"
                                    placeholder="9XXXXXXXXX"
                                    required>
                            </div>
                            <div class="form-text">Enter 10-digit number starting with 9 (e.g., 9123456789)</div>
                            @error('contact_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Status selection dropdown -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Right column: Location information -->
                    <div class="col-md-7">
                        <!-- Address input with autocomplete -->
                        <div class="mb-3 position-relative">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" autocomplete="off" required>{{ old('address') }}</textarea>
                            <div id="address-suggestions" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Interactive map for location selection -->
                        <div class="mb-3">
                            <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                        </div>
                        <!-- Latitude and longitude input fields -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude') }}">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude') }}">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Form action buttons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leaflet map resources -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Map initialization and interaction script -->
<script>
    // Initialize map centered on Manila
    let map = L.map('map').setView([14.5995, 120.9842], 13);
    let marker;
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Function to set marker and update coordinates
    function setMarker(lat, lng) {
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }

    // Set marker on map click
    map.on('click', function(e) {
        setMarker(e.latlng.lat, e.latlng.lng);
    });

    // Initialize marker if coordinates exist
    window.addEventListener('DOMContentLoaded', function() {
        let lat = document.getElementById('latitude').value;
        let lng = document.getElementById('longitude').value;
        if (lat && lng) {
            setMarker(lat, lng);
            map.setView([lat, lng], 16);
        }
    });

    // Address autocomplete functionality (Philippines only)
    const addressInput = document.getElementById('address');
    const suggestions = document.getElementById('address-suggestions');
    let debounceTimeout;
    addressInput.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        const query = this.value;
        if (query.length < 3) {
            suggestions.innerHTML = '';
            return;
        }
        debounceTimeout = setTimeout(() => {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&countrycodes=ph&q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestions.innerHTML = '';
                    data.slice(0, 5).forEach(item => {
                        const option = document.createElement('button');
                        option.type = 'button';
                        option.className = 'list-group-item list-group-item-action';
                        option.textContent = item.display_name;
                        option.onclick = () => {
                            addressInput.value = item.display_name;
                            setMarker(item.lat, item.lon);
                            map.setView([item.lat, item.lon], 17);
                            suggestions.innerHTML = '';
                        };
                        suggestions.appendChild(option);
                    });
                });
        }, 300);
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!addressInput.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.innerHTML = '';
        }
    });
</script>
@endsection 