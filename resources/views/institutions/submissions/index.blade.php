@extends('layouts.app')
@extends('layouts.adds')
@section('content')
<div class="container">
    <h2>All Submissions</h2>

    <!-- Button to open Create Modal -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createSubmissionModal">Create New Submission</button>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Attachment</th>
                <th>Status</th>
                <th>Submitted At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($submissions as $submission)
            <tr>
                <td>{{ $submission->id }}</td>
                <td>{{ $submission->title }}</td>
                <td>{{ Str::limit($submission->description, 50) }}</td>
                <td>
                    @if($submission->attachment)
                        <a href="{{ asset('storage/' . $submission->attachment) }}" target="_blank">View</a>
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ ucfirst($submission->status) }}</td>
                <td>{{ $submission->submitted_at }}</td>
                <td>
                    <!-- Show modal trigger -->
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#showSubmissionModal" data-submission="{{ json_encode($submission) }}">View</button>

                    <!-- Edit modal trigger -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSubmissionModal" data-submission="{{ json_encode($submission) }}">Edit</button>

                    <form action="{{ route('submissions.destroy', $submission->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12">No submissions found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Create Submission Modal -->
<div class="modal fade" id="createSubmissionModal" tabindex="-1" aria-labelledby="createSubmissionLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('submissions.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="createSubmissionLabel">Create New Submission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <!-- Form fields -->
          <div class="mb-3">
              <label for="citizen_id" class="form-label">Citizen ID</label>
              <input type="text" class="form-control" name="citizen_id" required>
          </div>
          <div class="mb-3">
              <label for="institution_id" class="form-label">Institution ID</label>
              <input type="text" class="form-control" name="institution_id" required>
          </div>
          <div class="mb-3">
              <label for="title" class="form-label">Title</label>
              <input type="text" class="form-control" name="title" required>
          </div>
          <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea class="form-control" name="description" rows="3" required></textarea>
          </div>
          <div class="mb-3">
              <label for="attachment" class="form-label">Attachment</label>
              <input type="file" class="form-control" name="attachment">
          </div>
          <div class="mb-3">
              <label for="status" class="form-label">Status</label>
              <select name="status" class="form-select" required>
                  <option value="pending">Pending</option>
                  <option value="approved">Approved</option>
                  <option value="rejected">Rejected</option>
              </select>
          </div>
          <div class="mb-3">
              <label for="submitted_at" class="form-label">Submitted At</label>
              <input type="datetime-local" class="form-control" name="submitted_at" required>
          </div>
          <div class="mb-3">
              <label for="reviewed_at" class="form-label">Reviewed At</label>
              <input type="datetime-local" class="form-control" name="reviewed_at">
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Submission</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Show Submission Modal -->
<div class="modal fade" id="showSubmissionModal" tabindex="-1" aria-labelledby="showSubmissionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showSubmissionLabel">Submission Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr><th>ID</th><td id="show-id"></td></tr>
            <tr><th>Citizen ID</th><td id="show-citizen_id"></td></tr>
            <tr><th>Institution ID</th><td id="show-institution_id"></td></tr>
            <tr><th>Title</th><td id="show-title"></td></tr>
            <tr><th>Description</th><td id="show-description"></td></tr>
            <tr><th>Attachment</th><td id="show-attachment"></td></tr>
            <tr><th>Status</th><td id="show-status"></td></tr>
            <tr><th>Submitted At</th><td id="show-submitted_at"></td></tr>
            <tr><th>Reviewed At</th><td id="show-reviewed_at"></td></tr>
            <tr><th>Created At</th><td id="show-created_at"></td></tr>
            <tr><th>Updated At</th><td id="show-updated_at"></td></tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Submission Modal -->
<div class="modal fade" id="editSubmissionModal" tabindex="-1" aria-labelledby="editSubmissionLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editSubmissionForm" method="POST" enctype="multipart/form-data" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="editSubmissionLabel">Edit Submission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" name="id" id="edit-id">
          <div class="mb-3">
              <label for="edit-citizen_id" class="form-label">Citizen ID</label>
              <input type="text" class="form-control" name="citizen_id" id="edit-citizen_id" required>
          </div>
          <div class="mb-3">
              <label for="edit-institution_id" class="form-label">Institution ID</label>
              <input type="text" class="form-control" name="institution_id" id="edit-institution_id" required>
          </div>
          <div class="mb-3">
              <label for="edit-title" class="form-label">Title</label>
              <input type="text" class="form-control" name="title" id="edit-title" required>
          </div>
          <div class="mb-3">
              <label for="edit-description" class="form-label">Description</label>
              <textarea class="form-control" name="description" id="edit-description" rows="3" required></textarea>
          </div>
          <div class="mb-3">
              <label for="edit-attachment" class="form-label">Attachment</label>
              <input type="file" class="form-control" name="attachment" id="edit-attachment">
              <small class="form-text text-muted">Leave empty to keep existing attachment.</small>
          </div>
          <div class="mb-3">
              <label for="edit-status" class="form-label">Status</label>
              <select name="status" class="form-select" id="edit-status" required>
                  <option value="pending">Pending</option>
                  <option value="approved">Approved</option>
                  <option value="rejected">Rejected</option>
              </select>
          </div>
          <div class="mb-3">
              <label for="edit-submitted_at" class="form-label">Submitted At</label>
              <input type="datetime-local" class="form-control" name="submitted_at" id="edit-submitted_at" required>
          </div>
          <div class="mb-3">
              <label for="edit-reviewed_at" class="form-label">Reviewed At</label>
              <input type="datetime-local" class="form-control" name="reviewed_at" id="edit-reviewed_at">
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update Submission</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Show Submission Modal population
    var showModal = document.getElementById('showSubmissionModal');
    showModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var submission = JSON.parse(button.getAttribute('data-submission'));

        document.getElementById('show-id').textContent = submission.id;
        document.getElementById('show-citizen_id').textContent = submission.citizen_id;
        document.getElementById('show-institution_id').textContent = submission.institution_id;
        document.getElementById('show-title').textContent = submission.title;
        document.getElementById('show-description').textContent = submission.description;
        if(submission.attachment){
            document.getElementById('show-attachment').innerHTML = `<a href="/storage/${submission.attachment}" target="_blank">View Attachment</a>`;
        } else {
            document.getElementById('show-attachment').textContent = 'N/A';
        }
        document.getElementById('show-status').textContent = submission.status.charAt(0).toUpperCase() + submission.status.slice(1);
        document.getElementById('show-submitted_at').textContent = submission.submitted_at;
        document.getElementById('show-reviewed_at').textContent = submission.reviewed_at ?? 'N/A';
        document.getElementById('show-created_at').textContent = submission.created_at;
        document.getElementById('show-updated_at').textContent = submission.updated_at;
    });

    // Edit Submission Modal population
    var editModal = document.getElementById('editSubmissionModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var submission = JSON.parse(button.getAttribute('data-submission'));

        document.getElementById('edit-id').value = submission.id;
        document.getElementById('edit-citizen_id').value = submission.citizen_id;
        document.getElementById('edit-institution_id').value = submission.institution_id;
        document.getElementById('edit-title').value = submission.title;
        document.getElementById('edit-description').value = submission.description;
        document.getElementById('edit-status').value = submission.status;
        document.getElementById('edit-submitted_at').value = submission.submitted_at ? submission.submitted_at.replace(' ', 'T') : '';
        document.getElementById('edit-reviewed_at').value = submission.reviewed_at ? submission.reviewed_at.replace(' ', 'T') : '';

        // Update the form action dynamically for each submission
        document.getElementById('editSubmissionForm').action = `/institutions/submissions/${submission.id}`;
    });

});
</script>
@endsection

@include('layouts.footer')