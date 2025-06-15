@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <!-- Header section with title and action buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-send"></i> Emergency Reports</h1>
            <div class="d-flex gap-2">
                <!-- Create button -->
                <a href="{{ route('admin.emergencies.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Create Emergency
                </a>
                <!-- Search form -->
                <form action="{{ route('admin.emergencies.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control form-control-sm" name="search"
                            placeholder="Search emergencies..." value="{{ request('search') }}">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                        @if (request('search'))
                            <a href="{{ route('admin.emergencies.index') }}" class="btn btn-secondary btn-sm"><i
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

        <!-- Dispatches table and map section -->
        <div class="row">
            <!-- Table section -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover emergency-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 30px;"></th>
                                        <th class="text-center"
                                            style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            Incident</th>
                                        <th class="text-center">Location</th>
                                        <th class="text-center">Created At</th>
                                        <th class="text-center">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($emergencies as $emergency)
                                        <tr>
                                            <td class="text-center">
                                                @if($emergency->status === 'unread')
                                                    <div class="unread-bullet"></div>
                                                @endif
                                            </td>
                                            <td class="text-center"
                                                style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                {{ $emergency->incident }}</td>
                                            <td class="text-center">
                                                <a href="#" class="view-location"
                                                    data-lat="{{ $emergency->latitude }}"
                                                    data-lng="{{ $emergency->longitude }}">
                                                    View
                                                </a>
                                            </td>
                                            <td class="text-center">{{ $emergency->created_at->format('M d, Y H:i') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.emergencies.show', $emergency) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No emergencies found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="me-3">
                                    Showing {{ $emergencies->firstItem() }} to {{ $emergencies->lastItem() }}
                                    of {{ $emergencies->total() }} results
                                </span>
                            </div>
                            <div>
                                {{ $emergencies->links('pagination::simple-bootstrap-5') }}
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

            // Store markers in an array for later reference
            const emergencyMarkers = [];

            // Add markers for each emergency
            @foreach ($emergencies as $emergency)
                const marker = L.marker([{{ $emergency->latitude }}, {{ $emergency->longitude }}])
                    .bindPopup(`
                        <strong>Incident:</strong> {{ $emergency->incident }}<br>
                        <strong>Reported By:</strong> {{ $emergency->user->name }}<br>
                        <strong>Time:</strong> {{ $emergency->created_at->format('M d, Y H:i') }}
                    `)
                    .addTo(map);
                emergencyMarkers.push(marker);
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

            // Initialize Pusher
            const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                encrypted: true
            });

            const channel = pusher.subscribe('emergencies');

            channel.bind('new-emergency', function(data) {
                // Update table
                const tableBody = document.querySelector('.emergency-table tbody');

                if (tableBody) {
                    // Remove "No emergencies" message if it exists
                    const noEmergencyRow = tableBody.querySelector('tr td[colspan="5"]');
                    if (noEmergencyRow) {
                        noEmergencyRow.parentElement.remove();
                    }

                    try {
                        const row = `
                            <tr>
                                <td class="text-center">
                                    <div class="unread-bullet"></div>
                                </td>
                                <td class="text-center" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    ${data.incident}
                                </td>
                                <td class="text-center">
                                    <a href="#" class="view-location" data-lat="${data.latitude}" data-lng="${data.longitude}">
                                        View
                                    </a>
                                </td>
                                <td class="text-center">${new Date(data.created_at).toLocaleString('en-US', {
                                    month: 'short',
                                    day: 'numeric',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}</td>
                                <td class="text-center">
                                    <a href="/admin/emergencies/${data.id}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>`;
                        tableBody.insertAdjacentHTML('afterbegin', row);

                        // Add click handler to the new view location link
                        const newLink = tableBody.querySelector('tr:first-child .view-location');
                        if (newLink) {
                            newLink.addEventListener('click', function(e) {
                                e.preventDefault();
                                const lat = this.dataset.lat;
                                const lng = this.dataset.lng;
                                map.setView([lat, lng], 15);

                                // Find and open the popup for the clicked location
                                emergencyMarkers.forEach(marker => {
                                    const markerLatLng = marker.getLatLng();
                                    if (markerLatLng.lat === parseFloat(lat) && markerLatLng.lng === parseFloat(lng)) {
                                        marker.openPopup();
                                    }
                                });
                            });
                        }
                    } catch (error) {
                        console.error('Error updating table:', error);
                    }
                }

                // Update map
                if (data.latitude && data.longitude) {
                    try {
                        const marker = L.marker([data.latitude, data.longitude])
                            .addTo(map)
                            .bindPopup(`
                                <strong>Incident:</strong> ${data.incident}<br>
                                <strong>Reported By:</strong> ${data.user.name}<br>
                                <strong>Time:</strong> ${new Date(data.created_at).toLocaleString()}
                            `);
                        emergencyMarkers.push(marker);

                        // Adjust map bounds to include new marker
                        const markerGroup = L.featureGroup(emergencyMarkers);
                        map.fitBounds(markerGroup.getBounds().pad(0.2));
                    } catch (error) {
                        console.error('Error updating map:', error);
                    }
                }
            });

            // Log any Pusher errors
            pusher.connection.bind('error', function(err) {
                console.error('Pusher connection error:', err);
            });
        });
    </script>

    <style>
        .unread-bullet {
            width: 12px;
            height: 12px;
            background-color: red;
            border-radius: 50%;
            display: inline-block;
        }
    </style>
@endsection
