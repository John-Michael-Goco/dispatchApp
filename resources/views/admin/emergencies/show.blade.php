@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h2 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-info-circle"></i> Emergency Details</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h5>Incident Description</h5>
                            <p>{{ $emergency->incident }}</p>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5>Latitude</h5>
                                <p>{{ $emergency->latitude }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Longitude</h5>
                                <p>{{ $emergency->longitude }}</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h5>Reported By</h5>
                            <p>{{ $emergency->user->name }}</p>
                        </div>

                        <div class="mb-3">
                            <h5>Created At</h5>
                            <p>{{ $emergency->created_at->format('M d, Y H:i') }}</p>
                        </div>

                        <!-- Map Container -->
                        <div class="mb-3">
                            <h5>Location</h5>
                            <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.emergencies.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            <div>
                                <a href="{{ route('admin.emergencies.edit', $emergency) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil"></i> Edit Emergency
                                </a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="bi bi-trash"></i> Delete Emergency
                                </button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map centered on Philippines
            const map = L.map('map').setView([14.5995, 120.9842], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Add marker for the emergency location
            const lat = {{ $emergency->latitude }};
            const lng = {{ $emergency->longitude }};
            L.marker([lat, lng]).addTo(map).bindPopup(`
                <strong>Incident:</strong> {{ $emergency->incident }}<br>
                <strong>Reported By:</strong> {{ $emergency->user->name }}<br>
                <strong>Time:</strong> {{ $emergency->created_at->format('M d, Y H:i') }}
            `);

            // Center map on the emergency location
            map.setView([lat, lng], 15);
        });
    </script>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this emergency?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.emergencies.destroy', $emergency) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 