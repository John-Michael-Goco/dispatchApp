@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-gear"></i> Services</h1>
            <div class="d-flex gap-2">
                <!-- Create new service button -->
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#createServiceModal">
                    <i class="bi bi-plus-circle"></i> Add New Service
                </button>
                <!-- Search form for filtering services -->
                <form action="{{ route('admin.services.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control form-control-sm" name="search"
                            placeholder="Search services..." value="{{ request('search') }}">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                        @if (request('search'))
                            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary btn-sm"><i
                                    class="bi bi-x-circle"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }} </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }} </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 250px;">Name</th>
                                <th>Description</th>
                                <th class="text-center" style="width: 100px;">Branches</th>
                                <th class="text-center" style="width: 100px;">Responders</th>
                                <th class="text-center" style="width: 150px;">Created At</th>
                                <th class="text-center" style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                                <tr>
                                    <td class="text-center fw-semibold">{{ $service->name }}</td>
                                    <td class="text-muted">{{ Str::limit($service->description, 50) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $service->branches()->count() }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $service->responders()->count() }}</span>
                                    </td>
                                    <td class="text-center text-muted">{{ $service->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#editServiceModal{{ $service->id }}" title="Edit Service">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteServiceModal{{ $service->id }}" title="Delete Service">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editServiceModal{{ $service->id }}" tabindex="-1"
                                            aria-labelledby="editServiceModalLabel{{ $service->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="editServiceModalLabel{{ $service->id }}">Edit Service</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.services.update', $service) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="name{{ $service->id }}"
                                                                    class="form-label">Service Name</label>
                                                                <input type="text"
                                                                    class="form-control @error('name') is-invalid @enderror"
                                                                    id="name{{ $service->id }}" name="name"
                                                                    value="{{ old('name', $service->name) }}" required>
                                                                @error('name')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="description{{ $service->id }}"
                                                                    class="form-label">Description</label>
                                                                <textarea class="form-control @error('description') is-invalid @enderror" id="description{{ $service->id }}"
                                                                    name="description" rows="3">{{ old('description', $service->description) }}</textarea>
                                                                @error('description')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update
                                                                Service</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteServiceModal{{ $service->id }}" tabindex="-1"
                                            aria-labelledby="deleteServiceModalLabel{{ $service->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="deleteServiceModalLabel{{ $service->id }}">Delete Service
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete the service "{{ $service->name }}"?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.services.destroy', $service) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No services found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <span class="me-3">
                            Showing {{ $services->firstItem() }} to {{ $services->lastItem() }}
                            of {{ $services->total() }} results
                        </span>
                    </div>
                    <div>
                        {{ $services->links('pagination::simple-bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Service Modal -->
    <div class="modal fade" id="createServiceModal" tabindex="-1" aria-labelledby="createServiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createServiceModalLabel">Add New Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.services.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Service Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
