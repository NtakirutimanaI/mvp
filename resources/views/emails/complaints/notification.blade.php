@component('mail::message')
# New Complaint Submitted

**Subject:** {{ $complaint->subject }}

**Category:** {{ $complaint->category }}

**Description:**
{{ $complaint->description }}

@if($complaint->institution)
**Assigned Institution:** {{ $complaint->institution->name }}
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
