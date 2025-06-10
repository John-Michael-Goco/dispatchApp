@extends('layouts.admin.app')

@section('content')
<div class="container">
    <!-- Header section with title and action buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-building"></i> Branch Details</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil"></i> Edit Branch
            </a>
            <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Main content card -->
    <div class="card shadow">
        <div class="card-body">
            <div class="row">
                <!-- Left column: Basic Information -->
                <div class="col-md-5">
                    <div class="mb-4">
                        <h5 class="card-title mb-3">Basic Information</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 150px;">Name:</th>
                                    <td>{{ $branch->name }}</td>
                                </tr>
                                <tr>
                                    <th>Service:</th>
                                    <td>{{ $branch->service->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Contact Number:</th>
                                    <td>{{ $branch->contact_number }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge px-3 py-2 {{ $branch->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($branch->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ $branch->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $branch->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right column: Location Information -->
                <div class="col-md-7">
                    <div class="mb-4">
                        <h5 class="card-title mb-3">Location Information</h5>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Address:</label>
                            <p class="mb-3">{{ $branch->address }}</p>
                        </div>
                        <!-- Map display -->
                        <div id="map" style="height: 300px; width: 100%; border-radius: 8px;" class="mb-3"></div>
                        <div class="row">
                            <div class="col">
                                <label class="form-label fw-bold">Latitude:</label>
                                <p>{{ $branch->latitude }}</p>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold">Longitude:</label>
                                <p>{{ $branch->longitude }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet map resources -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Map initialization script -->
<script>
    // Initialize map
    let map = L.map('map').setView([{{ $branch->latitude }}, {{ $branch->longitude }}], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Add marker for branch location
    L.marker([{{ $branch->latitude }}, {{ $branch->longitude }}])
        .addTo(map)
        .bindPopup('{{ $branch->name }}')
        .openPopup();
</script>
@endsection
