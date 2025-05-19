<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResponseController extends Controller
{
   public function store(Request $request, Submission $submission) {
    $request->validate([
        'response_text' => 'required'
    ]);

    Response::create([
        'submission_id' => $submission->id,
        'response_text' => $request->response_text
    ]);

    $submission->update(['status' => 'Resolved']);

    return back()->with('success', 'Response sent to citizen.');
}

}
