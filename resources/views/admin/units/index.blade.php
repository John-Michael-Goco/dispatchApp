@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <!-- Header section with title and action buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-truck"></i> Units</h1>
            <div class="d-flex gap-2">
                <!-- Create button -->
                <a href="{{ route('admin.units.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Create Unit
                </a>
                <!-- Search form -->
                <form action="{{ route('admin.units.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control form-control-sm" name="search"
                            placeholder="Search units..." value="{{ request('search') }}">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                        @if (request('search'))
                            <a href="{{ route('admin.units.index') }}" class="btn btn-secondary btn-sm"><i
                                    class="bi bi-x-circle"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Flash message for success notifications -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Units table and map section -->
        <div class="row">
            <!-- Table section -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Unit Code</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Location</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($units as $unit)
                                        <tr>
                                            <td class="text-center">{{ $unit->unit_code }}</td>
                                            <td class="text-center">{{ $unit->unit_type }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $unit->status === 'active' ? 'success' : ($unit->status === 'inactive' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($unit->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="view-location" data-lat="{{ $unit->latitude }}"
                                                    data-lng="{{ $unit->longitude }}">
                                                    View Location
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.units.show', $unit) }}" class="btn btn-info btn-sm">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No units found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="me-3">
                                    Showing {{ $units->firstItem() }} to {{ $units->lastItem() }}
                                    of {{ $units->total() }} results
                                </span>
                            </div>
                            <div>
                                {{ $units->links('pagination::simple-bootstrap-5') }}
                            </div>
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

            // Add markers for each unit
            @foreach ($units as $unit)
                L.marker([{{ $unit->latitude }}, {{ $unit->longitude }}])
                    .bindPopup(`
                    <strong>Unit Code:</strong> {{ $unit->unit_code }}<br>
                    <strong>Type:</strong> {{ $unit->unit_type }}<br>
                    <strong>Status:</strong> {{ ucfirst($unit->status) }}
                `)
                    .addTo(map);
            @endforeach

            // Handle view location clicks
            document.querySelectorAll('.view-location').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const lat = this.dataset.lat;
                    const lng = this.dataset.lng;
                    map.setView([lat, lng], 15);
                });
            });
        });
    </script>
@endsection 