<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Complaint;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
{
    $feedbacks = Feedback::with(['complaint', 'user'])->latest()->paginate(10);
    $complaints = Complaint::all(); // For the modal dropdown

    return view('feedbacks.index', compact('feedbacks', 'complaints'));
}
    public function create()
{
    // If you need to pass data to the form (e.g., complaints list)
    $complaints = \App\Models\Complaint::all();

    return view('feedbacks.create', compact('complaints'));
}
    public function store(Request $request)
    {
        $request->validate([
            'complaint_id' => 'required|exists:complaints,id',
            'response' => 'required|string',
        ]);

        Feedback::create([
            'complaint_id' => $request->complaint_id,
            'user_id' => auth()->id(), // assuming logged in user
            'response' => $request->response,
        ]);

        return redirect()->route('feedbacks.index')->with('success', 'Feedback submitted.');
    }
 
public function feedback()
{
    $feedbacks = Feedback::with('complaint', 'user')->latest()->paginate(10);
    $complaints = Complaint::all();

    return view('feedbacks.index', compact('feedbacks', 'complaints'));
}


}
