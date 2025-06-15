@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <!-- Flash message for success notifications -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header">
                        <h2 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-pencil-square"></i> Edit User</h2>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.users.update', $user) }}" method="POST" id="editUserForm">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                            pattern="[0-9]{10}" maxlength="10" title="Please enter exactly 10 digits"
                                            required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">Enter exactly 10 digits</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password"
                                            placeholder="Leave blank to keep current password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">Only fill this if you want to change the password</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                        <select class="form-select @error('role') is-invalid @enderror" id="role"
                                            name="role" required>
                                            <option value="">Select Role</option>
                                            <option value="user"
                                                {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                            <option value="admin"
                                                {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="responder"
                                                {{ old('role', $user->role) == 'responder' ? 'selected' : '' }}>Responder
                                            </option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Additional Information -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email"
                                            value="{{ old('email', $user->userInfo->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                        <input type="date"
                                            class="form-control @error('date_of_birth') is-invalid @enderror"
                                            id="date_of_birth" name="date_of_birth"
                                            value="{{ old('date_of_birth', $user->userInfo->date_of_birth?->format('Y-m-d')) }}"
                                            required>
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                            required>{{ old('address', $user->userInfo->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Responder Information Card -->
                            <div id="responderCard" class="card my-3" style="display: {{ $user->role === 'responder' ? 'block' : 'none' }};">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="mb-0" style="font-size:1.2rem;"><i class="bi bi-truck"></i> Responder Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="responder_code" class="form-label">Responder Code</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                                <input type="text" class="form-control @error('responder_code') is-invalid @enderror" 
                                                    id="responder_code" name="responder_code" 
                                                    value="{{ old('responder_code', $user->responder?->responder_code) }}"
                                                    placeholder="Enter responder code">
                                                @error('responder_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="service_id" class="form-label">Service Type</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-truck"></i></span>
                                                <select class="form-select @error('service_id') is-invalid @enderror" 
                                                    id="service_id" name="service_id">
                                                    <option value="">Select Service</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id }}" 
                                                            {{ old('service_id', $user->responder?->service_id) == $service->id ? 'selected' : '' }}>
                                                            {{ $service->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('service_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <span class="spinner-border spinner-border-sm d-none" role="status"
                                        aria-hidden="true" id="submitSpinner"></span>
                                    <i class="bi bi-save"></i> Update User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Prevent double form submission
        const form = document.getElementById('editUserForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitSpinner = document.getElementById('submitSpinner');
        const roleSelect = document.getElementById('role');
        const responderCard = document.getElementById('responderCard');
        const serviceSelect = document.getElementById('service_id');
        const responderCode = document.getElementById('responder_code');

        // Show/hide responder fields based on role selection
        roleSelect.addEventListener('change', function() {
            if (this.value === 'responder') {
                responderCard.style.display = 'block';
                serviceSelect.required = true;
                responderCode.required = true;
            } else {
                responderCard.style.display = 'none';
                serviceSelect.required = false;
                responderCode.required = false;
            }
        });

        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitSpinner.classList.remove('d-none');
        });

        // Phone number validation
        const phoneInput = document.getElementById('phone');
        phoneInput.addEventListener('input', function(e) {
            // Remove any non-digit characters
            this.value = this.value.replace(/\D/g, '');
            // Limit to 10 digits
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    </script>
@endsection
