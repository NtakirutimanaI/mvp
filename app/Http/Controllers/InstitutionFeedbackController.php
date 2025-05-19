<?php

use App\Models\Complaint;
use App\Models\Feedback;
use Illuminate\Http\Request;

class InstitutionFeedbackController extends Controller
{
    // Show all complaints assigned to institution for feedback
    public function index()
    {
        $institutionId = auth()->user()->institution_id; // assuming user belongs to institution
        $complaints = Complaint::where('institution_id', $institutionId)->with('feedbacks')->get();

        return view('institution.feedbacks.index', compact('complaints'));
    }

    // Show form to respond to a complaint
    public function create($complaintId)
    {
        $complaint = Complaint::findOrFail($complaintId);
        return view('institution.feedbacks.create', compact('complaint'));
    }

    // Store feedback from institution
    public function store(Request $request, $complaintId)
    {
        $request->validate([
            'response' => 'required|string',
        ]);

        $complaint = Complaint::findOrFail($complaintId);

        Feedback::create([
            'complaint_id' => $complaint->id,
            'user_id' => auth()->id(),
            'response' => $request->response,
        ]);

        // Update complaint status
        $complaint->update(['status' => 'In Progress']);

        return redirect()->route('institution.feedbacks.index')->with('success', 'Response sent successfully.');
    }
}
