@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <!-- Header section with title and action buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-people"></i> Responders</h1>
            <div class="d-flex gap-2">
                <!-- Create button -->
                <a href="{{ route('admin.responders.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Add Responder
                </a>
                <!-- Search form -->
                <form action="{{ route('admin.responders.index') }}" method="GET" class="d-flex gap-2" id="filterForm">
                    <!-- Service filter dropdown -->
                    <select class="form-select form-select-sm" name="service_id" style="width: 200px;" onchange="this.form.submit()">
                        <option value="">All Services</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}"
                                {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control form-control-sm" name="search"
                            placeholder="Search responders..." value="{{ request('search') }}"
                            onkeyup="this.form.submit()">
                        @if (request('search') || request('service_id'))
                            <a href="{{ route('admin.responders.index') }}" class="btn btn-secondary btn-sm"><i
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

        <!-- Responders table and map section -->
        <div class="row">
            <!-- Table section -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Service</th>
                                        <th class="text-center">Location</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($responders as $responder)
                                        <tr>
                                            <td class="text-center">{{ $responder->responder_code }}</td>
                                            <td class="text-center">{{ $responder->service->name }}</td>
                                            <td class="text-center">
                                                <a href="#" class="view-location"
                                                    data-lat="{{ $responder->latitude }}"
                                                    data-lng="{{ $responder->longitude }}">
                                                    View
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-{{ $responder->status === 'active' ? 'success' : ($responder->status === 'busy' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($responder->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.responders.show', $responder) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $responder->id }}" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>

                                                <!-- Delete Confirmation Modal -->
                                                <div class="modal fade" id="deleteModal{{ $responder->id }}" tabindex="-1"
                                                    aria-labelledby="deleteModalLabel{{ $responder->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="deleteModalLabel{{ $responder->id }}">
                                                                    Confirm Delete
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete responder
                                                                <strong>{{ $responder->user->name }}</strong>?
                                                                This action cannot be undone.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <form
                                                                    action="{{ route('admin.responders.destroy', $responder) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">
                                                                        <i class="bi bi-trash"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No responders found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="me-3">
                                    Showing {{ $responders->firstItem() ?? 0 }} to {{ $responders->lastItem() ?? 0 }}
                                    of {{ $responders->total() }} results
                                </span>
                            </div>
                            <div>
                                {{ $responders->links() }}
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

            // Add markers for each responder with valid coordinates
            @foreach ($responders as $responder)
                @if ($responder->latitude != 0 && $responder->longitude != 0)
                    L.marker([{{ $responder->latitude }}, {{ $responder->longitude }}])
                        .bindPopup(`
                        <strong>Code:</strong> {{ $responder->responder_code }}<br>
                        <strong>Service:</strong> {{ $responder->service->name }}<br>
                        <strong>Status:</strong> {{ ucfirst($responder->status) }}
                    `)
                        .addTo(map);
                @endif
            @endforeach

            // Handle view location clicks
            document.querySelectorAll('.view-location').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const lat = this.dataset.lat;
                    const lng = this.dataset.lng;
                    // Only center map if coordinates are not 0,0
                    if (lat != 0 && lng != 0) {
                        map.setView([lat, lng], 15);
                    } else {
                        alert('No Location Update yet');
                    }
                });
            });
        });
    </script>
@endsection
