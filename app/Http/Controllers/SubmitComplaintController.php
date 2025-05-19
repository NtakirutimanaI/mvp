<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ComplaintNotification;

class SubmitComplaintController extends Controller
{
   
    // Show the submit complaint form
    public function create()
    {
        $categories = ['Water', 'Electricity', 'Roads', 'Healthcare', 'Education'];
        $institutions = Institution::all();

        return view('citizen.submit.complaint', compact('categories', 'institutions'));
    }

    // Store the complaint in DB and send notifications
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'institution_id' => 'nullable|exists:institutions,id',
        ]);

        // Create complaint
        $complaint = Complaint::create([
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'institution_id' => $validated['institution_id'] ?? null,
            'user_id' => Auth::id(),  // logged in user
            'status' => 'pending',    // default status
        ]);

        // Notify Institution if assigned
        if ($complaint->institution_id) {
            $institution = Institution::find($complaint->institution_id);
            if ($institution && $institution->email) {
                Mail::to($institution->email)->send(new ComplaintNotification($complaint));
            }
        }

        // Notify Admin(s)
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new ComplaintNotification($complaint));
        }

        // Redirect back with success message
        return redirect()->route('citizen.dashboard')->with('success', 'Your complaint has been submitted and sent to the relevant parties.');
    }
}

