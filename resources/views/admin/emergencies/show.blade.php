@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <!-- Header section with title and action buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="font-size:1.5rem;">
                <i class="bi bi-exclamation-triangle-fill text-danger"></i> Emergency Details
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.emergencies.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
                <a href="{{ route('admin.emergencies.edit', $emergency) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil"></i> Edit Emergency
                </a>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash"></i> Delete Emergency
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Emergency Details -->
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0"><i class="bi bi-info-circle"></i> Emergency Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Incident Description</h6>
                            <p class="fs-5">{{ $emergency->incident }}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Reported By</h6>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-primary text-white me-3">
                                    {{ substr($emergency->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $emergency->user->name }}</p>
                                    <small class="text-muted">{{ $emergency->user->email }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Reported At</h6>
                            <p class="mb-0">
                                <i class="bi bi-clock"></i> {{ $emergency->created_at->format('M d, Y H:i') }}
                            </p>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Coordinates</h6>
                            <p class="mb-0">
                                <i class="bi bi-geo-alt"></i> 
                                <span class="fw-semibold">Latitude:</span> {{ $emergency->latitude }} | 
                                <span class="fw-semibold">Longitude:</span> {{ $emergency->longitude }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Map -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0"><i class="bi bi-geo-alt"></i> Location</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 500px; width: 100%; border-radius: 0 0 8px 8px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle text-danger"></i> Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this emergency report?</p>
                    <p class="text-muted mb-0">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.emergencies.destroy', $emergency) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete Emergency
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet Map Integration -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map centered on the emergency location
            const lat = {{ $emergency->latitude }};
            const lng = {{ $emergency->longitude }};
            const map = L.map('map').setView([lat, lng], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Add marker for the emergency location
            const emergencyIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: #dc3545; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            L.marker([lat, lng], { icon: emergencyIcon }).addTo(map)
                .bindPopup(`
                    <div style="font-size: 14px;">
                        <strong>Emergency Location</strong><br>
                        <small class="text-muted">${lat}, ${lng}</small>
                    </div>
                `)
                .openPopup();
        });
    </script>
@endsection 