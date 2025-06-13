@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="mb-0" style="font-size:1.5rem;">
                            <i class="bi bi-person-circle"></i> User Details
                        </h2>
                        <div>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6 mb-4">
                                <h3 class="h5 mb-3 text-primary">Basic Information</h3>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Full Name</label>
                                    <p class="mb-0">{{ $user->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Phone Number</label>
                                    <p class="mb-0">{{ $user->phone }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Role</label>
                                    <p class="mb-0">
                                        <span class="badge bg-{{ 
                                            $user->role === 'admin' ? 'primary' : 
                                            ($user->role === 'responder' ? 'success' : 
                                            ($user->role === 'user' ? 'info' : 'secondary')) 
                                        }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-md-6 mb-4">
                                <h3 class="h5 mb-3 text-primary">Additional Information</h3>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email Address</label>
                                    <p class="mb-0">{{ $user->userInfo->email }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Date of Birth</label>
                                    <p class="mb-0">{{ $user->userInfo->date_of_birth ? $user->userInfo->date_of_birth->format('F j, Y') : 'Not set' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Address</label>
                                    <p class="mb-0">{{ $user->userInfo->address ?? 'Not set' }}</p>
                                </div>
                            </div>

                            <!-- Account Information -->
                            <div class="col-12">
                                <h3 class="h5 mb-3 text-primary">Account Information</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Created At</label>
                                        <p class="mb-0">{{ $user->created_at->format('F j, Y g:i A') }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Last Updated</label>
                                        <p class="mb-0">{{ $user->updated_at->format('F j, Y g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 