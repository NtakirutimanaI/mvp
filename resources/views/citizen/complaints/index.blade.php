@extends('layouts.app')
@extends('layouts.adds')

@section('content')
<div class="container">
    <h2 class="mb-4 d-flex justify-content-between align-items-center">
        All Complaints / Feedback
        <button class="btn btn-primary btn-sm ms-5" data-bs-toggle="modal" data-bs-target="#addComplaintModal">+ Add Complaint</button>
    </h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following errors:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Institution</th>
                <th>Subject</th>
                <th>Category</th>
                <th>Status</th>
                <th>Description</th>
                <th>Response</th>
                <th>Submitted At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $complaint)
                <tr>
                    <td>{{ $complaint->id }}</td>
                    <td>{{ $complaint->user->name ?? 'N/A' }}</td>
                    <td>{{ $complaint->institution->name ?? 'Unassigned' }}</td>
                    <td>{{ $complaint->subject }}</td>
                    <td>{{ $complaint->category }}</td>
                    <td>
                        @if($complaint->status === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($complaint->status === 'in_progress')
                            <span class="badge bg-info text-dark">In Progress</span>
                        @elseif($complaint->status === 'resolved')
                            <span class="badge bg-success">Resolved</span>
                        @endif
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit($complaint->description, 50) }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($complaint->response, 50) ?? 'N/A' }}</td>
                    <td>{{ $complaint->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewComplaintModal{{ $complaint->id }}">View</button>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editComplaintModal{{ $complaint->id }}">Edit</button>
                    </td>
                </tr>

                {{-- View Modal --}}
                <div class="modal fade" id="viewComplaintModal{{ $complaint->id }}" tabindex="-1" aria-labelledby="viewComplaintModalLabel{{ $complaint->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">View Complaint #{{ $complaint->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Subject:</strong> {{ $complaint->subject }}</p>
                                <p><strong>Category:</strong> {{ $complaint->category }}</p>
                                <p><strong>Description:</strong> {{ $complaint->description }}</p>
                                <p><strong>Response:</strong> {{ $complaint->response ?? 'N/A' }}</p>
                                <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</p>
                                <p><strong>Institution:</strong> {{ $complaint->institution->name ?? 'Unassigned' }}</p>
                                <p><strong>Submitted By:</strong> {{ $complaint->user->name ?? 'N/A' }}</p>
                                <p><strong>Submitted At:</strong> {{ $complaint->created_at }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Edit Modal --}}
                <div class="modal fade" id="editComplaintModal{{ $complaint->id }}" tabindex="-1" aria-labelledby="editComplaintModalLabel{{ $complaint->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form action="{{ route('complaints.update', $complaint->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Complaint #{{ $complaint->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Subject</label>
                                        <input type="text" name="subject" value="{{ $complaint->subject }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Category</label>
                                        <input type="text" name="category" value="{{ $complaint->category }}" class="form-control" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" rows="4" class="form-control" required>{{ $complaint->description }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Institution</label>
                                        <select name="institution_id" class="form-select">
                                            <option value="">-- None --</option>
                                            @foreach($institutions as $institution)
                                                <option value="{{ $institution->id }}" {{ $complaint->institution_id == $institution->id ? 'selected' : '' }}>
                                                    {{ $institution->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $complaints->links() }}
    </div>
</div>

{{-- Add Complaint Modal --}}
<div class="modal fade" id="addComplaintModal" tabindex="-1" aria-labelledby="addComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('complaints.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Complaint / Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="institution_id" class="form-label">Institution (Optional)</label>
                        <select name="institution_id" class="form-select">
                            <option value="">-- None --</option>
                            @foreach($institutions as $institution)
                                <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit Complaint</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@include('layouts.footer')