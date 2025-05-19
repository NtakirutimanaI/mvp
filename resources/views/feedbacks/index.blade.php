@extends('layouts.app')
@extends('layouts.adds')

@section('content')
<div class="container">
    <h2>All Feedbacks</h2>

    <!-- Button to trigger modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#feedbackModal">
        Give Feedback
    </button>

    <!-- Feedback Modal -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form action="{{ route('feedbacks.store') }}" method="POST">
            @csrf
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">New Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">

                <div class="mb-3">
                    <label for="complaint_id" class="form-label">Select Complaint</label>
                    <select name="complaint_id" id="complaint_id" class="form-select" required>
                        <option value="">-- Select Complaint --</option>
                        @foreach($complaints as $complaint)
                            <option value="{{ $complaint->id }}">{{ $complaint->title ?? 'No Title' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="response" class="form-label">Your Response</label>
                    <textarea name="response" id="response" rows="4" class="form-control" required></textarea>
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit Feedback</button>
              </div>
            </div>
        </form>
      </div>
    </div>

    <!-- Feedbacks Table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Complaint</th>
                <th>Response</th>
                <th>User</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feedbacks as $feedback)
                <tr>
                    <td>{{ $feedback->id }}</td>
                    <td>{{ $feedback->complaint->title ?? 'N/A' }}</td>
                    <td>{{ $feedback->response }}</td>
                    <td>{{ $feedback->user->name ?? 'Unknown' }}</td>
                    <td>{{ $feedback->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No feedbacks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div>
        {{ $feedbacks->links() }}
    </div>
</div>
@endsection

@section('scripts')
<!-- Bootstrap 5 JS (make sure Bootstrap CSS is loaded in your layout) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection


@include('layouts.footer')