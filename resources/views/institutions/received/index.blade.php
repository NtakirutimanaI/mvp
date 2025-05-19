@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Received Reports</h2>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Institution</th>
                <th>Subject</th>
                <th>Description</th>
                <th>Status</th>
                <th>Category</th>
                <th>Response</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($receiveds as $received)
                <tr>
                    <td>{{ $received->id }}</td>
                    <td>{{ $received->user->name ?? 'N/A' }}</td>
                    <td>{{ $received->institution->name ?? 'N/A' }}</td>
                    <td>{{ $received->subject }}</td>
                    <td>{{ Str::limit($received->description, 50) }}</td>
                    <td>
                        <span class="badge 
                            @if($received->status == 'pending') bg-warning 
                            @elseif($received->status == 'in_progress') bg-primary 
                            @else bg-success 
                            @endif">
                            {{ ucfirst($received->status) }}
                        </span>
                    </td>
                    <td>{{ $received->category }}</td>
                    <td>{{ Str::limit($received->response, 50) ?? 'No response yet' }}</td>
                    <td>{{ $received->created_at?->format('Y-m-d H:i') }}</td>
                    <td>{{ $received->updated_at?->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $receiveds->links() }}
    </div>
</div>
@endsection
