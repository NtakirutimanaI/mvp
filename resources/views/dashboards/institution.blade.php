@extends('layouts.app')
@extends('layouts.adds')
@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-4">
    <h2>Institution Dashboard</h2>

    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white p-3">
                <h5>Total Users</h5>
                <h3>{{ $totalUsers ?? 0 }}</h3> {{-- Show 0 if variable is missing --}}
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white p-3">
                <h5>Total Institutions</h5>
                <h3>{{ $totalInstitutions ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white p-3">
                <h5>Total Categories</h5>
                <h3>{{ $totalCategories ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white p-3">
                <h5>Total Complaints</h5>
                <h3>{{ $totalComplaints ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection
