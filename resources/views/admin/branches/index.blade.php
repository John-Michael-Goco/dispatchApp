@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-diagram-3"></i> Branches</h1>
            <div class="d-flex gap-2">
                <!-- Create new branch button -->
                <a href="{{ route('admin.branches.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Add New Branch
                </a>
                <!-- Filter and search form -->
                <form action="{{ route('admin.branches.index') }}" method="GET" class="d-flex gap-2" id="filterForm">
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
                    <!-- Search input and buttons -->
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control form-control-sm" name="search"
                            placeholder="Search branches..." value="{{ request('search') }}"
                            onkeyup="this.form.submit()">
                        @if (request('search') || request('service_id'))
                            <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary btn-sm"><i
                                    class="bi bi-x-circle"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 200px;">Name</th>
                                <th class="text-center" style="width: 150px;">Service</th>
                                <th>Address</th>
                                <th class="text-center" style="width: 150px;">Contact Number</th>
                                <th class="text-center" style="width: 100px;">Status</th>
                                <th class="text-center" style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($branches as $branch)
                                <tr>
                                    <td class="text-center fw-semibold">{{ $branch->name }}</td>
                                    <td class="text-center">{{ $branch->service->name ?? '-' }}</td>
                                    <td class="text-muted">{{ Str::limit($branch->address, 50) }}</td>
                                    <td class="text-center">{{ $branch->contact_number }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <span class="badge px-3 py-2 {{ $branch->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($branch->status) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.branches.show', $branch) }}" class="btn btn-sm btn-primary" title="View Branch">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-sm btn-info" title="Edit Branch">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteBranchModal{{ $branch->id }}" title="Delete Branch">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteBranchModal{{ $branch->id }}" tabindex="-1"
                                            aria-labelledby="deleteBranchModalLabel{{ $branch->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="deleteBranchModalLabel{{ $branch->id }}">Delete Branch</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete the branch "{{ $branch->name }}"?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.branches.destroy', $branch) }}"
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
                                    <td colspan="6" class="text-center">No branches found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <span class="me-3">
                            Showing {{ $branches->firstItem() }} to {{ $branches->lastItem() }}
                            of {{ $branches->total() }} results
                        </span>
                    </div>
                    <div>
                        {{ $branches->links('pagination::simple-bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
