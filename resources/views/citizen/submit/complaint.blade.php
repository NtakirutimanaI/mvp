@extends('layouts.app')
@extends('layouts.adds')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Submit a Complaint / Feedback</h2>

    @if(session('success'))
        <div class="alert alert-success">
            <strong>Complaint Submitted:</strong> {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('citizen.complaints.store') }}">
        @csrf

        <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input 
                type="text" 
                class="form-control @error('subject') is-invalid @enderror" 
                id="subject" 
                name="subject" 
                value="{{ old('subject') }}" 
                required>
            @error('subject')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea 
                class="form-control @error('description') is-invalid @enderror" 
                id="description" 
                name="description" 
                rows="4" 
                required>{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select 
                class="form-select @error('category') is-invalid @enderror" 
                id="category" 
                name="category" 
                required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="institution_id" class="form-label">Assign to Institution (Optional)</label>
            <select 
                class="form-select @error('institution_id') is-invalid @enderror" 
                id="institution_id" 
                name="institution_id">
                <option value="">Select Institution</option>
                @foreach($institutions as $institution)
                    <option value="{{ $institution->id }}" {{ old('institution_id') == $institution->id ? 'selected' : '' }}>
                        {{ $institution->name }}
                    </option>
                @endforeach
            </select>
            @error('institution_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit Complaint</button>
    </form>
</div>
@endsection

@include('layouts.footer')
