@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="mb-0" style="font-size:1.5rem;">
                            <i class="bi bi-person-circle"></i> Responder Details
                        </h2>
                        <div>
                            <a href="{{ route('admin.responders.edit', $responder) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('admin.responders.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6 mb-4">
                                <h3 class="h5 mb-3 text-primary">Basic Information</h3>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Full Name</label>
                                    <p class="mb-0">{{ $responder->user->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Phone Number</label>
                                    <p class="mb-0">{{ $responder->user->phone }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email Address</label>
                                    <p class="mb-0">{{ $responder->user->userInfo->email }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Address</label>
                                    <p class="mb-0">{{ $responder->user->userInfo->address }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Date of Birth</label>
                                    <p class="mb-0">{{ $responder->user->userInfo->date_of_birth->format('F d, Y') }}</p>
                                </div>
                            </div>

                            <!-- Responder Information -->
                            <div class="col-md-6 mb-4">
                                <h3 class="h5 mb-3 text-primary">Responder Information</h3>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Responder Code</label>
                                    <p class="mb-0">{{ $responder->responder_code }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Service</label>
                                    <p class="mb-0">{{ $responder->service->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <p class="mb-0">
                                        <span class="badge bg-{{ 
                                            $responder->status === 'active' ? 'success' : 
                                            ($responder->status === 'busy' ? 'warning' : 
                                            ($responder->status === 'maintenance' ? 'info' : 'danger')) 
                                        }}">
                                            {{ ucfirst($responder->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Location</label>
                                    <p class="mb-0">
                                        @if($responder->latitude != 0 && $responder->longitude != 0)
                                            <a href="#" class="view-location" 
                                                data-lat="{{ $responder->latitude }}"
                                                data-lng="{{ $responder->longitude }}">
                                                View on Map
                                            </a>
                                        @else
                                            <span class="text-muted">No location update yet</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Created At</label>
                                    <p class="mb-0">{{ $responder->created_at->format('F d, Y H:i') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Last Updated</label>
                                    <p class="mb-0">{{ $responder->updated_at->format('F d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Map Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="h5 mb-0">Location Map</h3>
                                    </div>
                                    <div class="card-body">
                                        <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

            // Add marker if coordinates are valid
            @if($responder->latitude != 0 && $responder->longitude != 0)
                L.marker([{{ $responder->latitude }}, {{ $responder->longitude }}])
                    .bindPopup(`
                    <strong>Code:</strong> {{ $responder->responder_code }}<br>
                    <strong>Service:</strong> {{ $responder->service->name }}<br>
                    <strong>Status:</strong> {{ ucfirst($responder->status) }}
                `)
                    .addTo(map);
            @endif

            // Handle view location clicks
            document.querySelectorAll('.view-location').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const lat = this.dataset.lat;
                    const lng = this.dataset.lng;
                    if (lat != 0 && lng != 0) {
                        map.setView([lat, lng], 15);
                    }
                });
            });
        });
    </script>
@endsection 