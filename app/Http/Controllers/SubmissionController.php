<?php
namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
 public function index()
{
    $submissions = \App\Models\Submission::all(); // adjust model namespace if needed
    return view('institutions.submissions.index', compact('submissions'));
}
public function store(Request $request)
{
    $validated = $request->validate([
        'citizen_id' => 'required|integer',
        'institution_id' => 'required|integer',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        // other validation rules
    ]);

    Submission::create($validated);

    return redirect()->route('submissions.index')->with('success', 'Submission created successfully.');
}


}
