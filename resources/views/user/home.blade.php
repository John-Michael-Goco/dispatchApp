@extends('layouts.user.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-4">Welcome, {{ Auth::user()->name }}</h2>
        </div>
    </div>

    <!-- User Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">My Tasks</h5>
                    <h2 class="mb-0">5</h2>
                    <small>Tasks assigned to you</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Completed</h5>
                    <h2 class="mb-0">12</h2>
                    <small>Tasks completed this week</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Service Hours</h5>
                    <h2 class="mb-0">24</h2>
                    <small>Hours logged this week</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks and Notifications -->
    <div class="row">
        <!-- My Tasks -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">My Tasks</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Service Call #123</h6>
                                <span class="badge bg-warning">Pending</span>
                            </div>
                            <p class="mb-1">Address: 123 Main St, City</p>
                            <small>Due: Today, 2:00 PM</small>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Maintenance Check #456</h6>
                                <span class="badge bg-info">In Progress</span>
                            </div>
                            <p class="mb-1">Address: 456 Oak Ave, Town</p>
                            <small>Due: Tomorrow, 10:00 AM</small>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Emergency Response #789</h6>
                                <span class="badge bg-danger">Urgent</span>
                            </div>
                            <p class="mb-1">Address: 789 Pine Rd, Village</p>
                            <small>Due: Today, 4:30 PM</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications and Quick Actions -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Notifications</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">New Task Assigned</h6>
                                <small>5 mins ago</small>
                            </div>
                            <p class="mb-1">You have been assigned to Service Call #123</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Task Updated</h6>
                                <small>1 hour ago</small>
                            </div>
                            <p class="mb-1">Maintenance Check #456 has been updated</p>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary">
                            <i class="bi bi-calendar-check"></i> View Schedule
                        </button>
                        <button class="btn btn-success">
                            <i class="bi bi-file-earmark-text"></i> Submit Report
                        </button>
                        <button class="btn btn-warning">
                            <i class="bi bi-exclamation-circle"></i> Report Issue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 