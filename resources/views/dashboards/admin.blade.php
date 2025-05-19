@extends('layouts.app')
@extends('layouts.adds')

@section('title', 'Admin Dashboard - Citizen Engagement System')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Admin Dashboard</h2>

    {{-- System Summary --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h6>Total Submissions</h6>
                    <h3>{{ $stats['total_submissions'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h6>Pending</h6>
                    <h3>{{ $stats['pending'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body">
                    <h6>In Progress</h6>
                    <h3>{{ $stats['in_progress'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h6>Resolved</h6>
                    <h3>{{ $stats['resolved'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart and Insights --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">Submission Status Overview</div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Simulated Top Categories --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">Top Complaint Categories</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($topCategories ?? [] as $cat)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $cat['name'] }}
                                <span class="badge bg-secondary rounded-pill">{{ $cat['count'] }}</span>
                            </li>
                        @endforeach
                        @if(empty($topCategories))
                            <li class="list-group-item text-muted">No data yet</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Complaints Table --}}
    <div class="card mb-5">
        <div class="card-header">
            <h5>Recent Citizen Feedback</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <th>Category</th>
                        <th>Agency</th>
                        <th>Status</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentSubmissions ?? [] as $index => $submission)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $submission->subject }}</td>
                            <td>{{ $submission->category->name ?? 'N/A' }}</td>
                            <td>{{ $submission->agency->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge
                                    @if($submission->status == 'Pending') bg-warning
                                    @elseif($submission->status == 'In Progress') bg-info
                                    @else bg-success
                                    @endif">
                                    {{ $submission->status }}
                                </span>
                            </td>
                            <td>{{ $submission->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">No submissions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Chart.js Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('statusChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Pending', 'In Progress', 'Resolved'],
        datasets: [{
            data: [
                {{ $stats['pending'] ?? 0 }},
                {{ $stats['in_progress'] ?? 0 }},
                {{ $stats['resolved'] ?? 0 }}
            ],
            backgroundColor: ['#f6c23e', '#36b9cc', '#1cc88a']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Complaint Status Distribution'
            },
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endsection

