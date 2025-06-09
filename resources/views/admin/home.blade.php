@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-4">Admin Dashboard</h2>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <h2 class="mb-0">150</h2>
                    <small>Active users in the system</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Active Services</h5>
                    <h2 class="mb-0">25</h2>
                    <small>Currently running services</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending Tasks</h5>
                    <h2 class="mb-0">12</h2>
                    <small>Tasks awaiting action</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Incidents</h5>
                    <h2 class="mb-0">45</h2>
                    <small>Reported incidents</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Quick Actions -->
    <div class="row">
        <!-- Recent Activity -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">New user registration</h6>
                                <small>3 mins ago</small>
                            </div>
                            <p class="mb-1">John Doe registered as a new user.</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Service update</h6>
                                <small>1 hour ago</small>
                            </div>
                            <p class="mb-1">Service #123 was updated by Admin.</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">New incident reported</h6>
                                <small>2 hours ago</small>
                            </div>
                            <p class="mb-1">Incident #456 was reported in Zone A.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Add New User
                        </button>
                        <button class="btn btn-success">
                            <i class="bi bi-gear"></i> Manage Services
                        </button>
                        <button class="btn btn-warning">
                            <i class="bi bi-exclamation-triangle"></i> View Incidents
                        </button>
                        <button class="btn btn-info">
                            <i class="bi bi-file-earmark-text"></i> Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 