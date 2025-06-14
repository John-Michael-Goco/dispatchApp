@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <!-- Header section with title and action buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-people"></i> Users</h1>
            <div class="d-flex gap-2">
                <!-- Create button -->
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Add New User
                </a>
                <!-- Search and filter form -->
                <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control form-control-sm" name="search"
                            placeholder="Search users..." value="{{ request('search') }}">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                        @if (request('search') || request('role'))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm"><i
                                    class="bi bi-x-circle"></i></a>
                        @endif
                    </div>
                    <select name="role" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                        <option value="">All Roles</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Users</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                        <option value="responder" {{ request('role') == 'responder' ? 'selected' : '' }}>Responders</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Flash message for success notifications -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Users table -->
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 200px;">Name</th>
                                <th>Email</th>
                                <th class="text-center" style="width: 120px;">Phone</th>
                                <th class="text-center" style="width: 120px;">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'role', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                        class="text-decoration-none text-dark">
                                        Role
                                        @if(request('sort') == 'role')
                                            <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="text-center" style="width: 150px;">Created At</th>
                                <th class="text-center" style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td class="text-center fw-semibold">{{ $user->name }}</td>
                                    <td class="text-muted">{{ $user->userInfo->email ?? 'N/A' }}</td>
                                    <td class="text-center text-muted">{{ $user->phone }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ 
                                            $user->role === 'admin' ? 'primary' : 
                                            ($user->role === 'responder' ? 'success' : 
                                            ($user->role === 'user' ? 'info' : 'secondary')) 
                                        }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-center text-muted">{{ $user->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                                class="btn btn-sm btn-info" title="View User">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                                class="btn btn-sm btn-primary" title="Edit User">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteUserModal{{ $user->id }}" title="Delete User">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1"
                                            aria-labelledby="deleteUserModalLabel{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteUserModalLabel{{ $user->id }}">
                                                            Delete User
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete the user "{{ $user->name }}"?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.users.destroy', $user) }}"
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
                                    <td colspan="6" class="text-center">No users found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <span class="me-3">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }}
                            of {{ $users->total() }} results
                        </span>
                    </div>
                    <div>
                        {{ $users->appends(request()->query())->links('pagination::simple-bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 