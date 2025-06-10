@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <!-- Header section with title and action buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-truck"></i> Unit Details</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Edit Unit
                </a>
                <a href="{{ route('admin.units.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to Units
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Details section -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px;">Unit Code</th>
                                        <td>{{ $unit->unit_code }}</td>
                                    </tr>
                                    <tr>
                                        <th>Unit Type</th>
                                        <td>{{ $unit->unit_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge bg-{{ $unit->status === 'active' ? 'success' : ($unit->status === 'inactive' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($unit->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Latitude</th>
                                        <td>{{ $unit->latitude }}</td>
                                    </tr>
                                    <tr>
                                        <th>Longitude</th>
                                        <td>{{ $unit->longitude }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $unit->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>{{ $unit->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Delete button -->
                        <div class="mt-4">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUnitModal">
                                <i class="bi bi-trash"></i> Delete Unit
                            </button>
                        </div>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteUnitModal" tabindex="-1" aria-labelledby="deleteUnitModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUnitModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the unit <strong>{{ $unit->unit_code }}</strong>? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Unit</button>
                    </form>
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
            // Initialize map centered on unit location
            const map = L.map('map').setView([{{ $unit->latitude }}, {{ $unit->longitude }}], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Add marker for the unit
            L.marker([{{ $unit->latitude }}, {{ $unit->longitude }}])
                .bindPopup(`
                    <strong>Unit Code:</strong> {{ $unit->unit_code }}<br>
                    <strong>Type:</strong> {{ $unit->unit_type }}<br>
                    <strong>Status:</strong> {{ ucfirst($unit->status) }}
                `)
                .addTo(map);
        });
    </script>
@endsection 