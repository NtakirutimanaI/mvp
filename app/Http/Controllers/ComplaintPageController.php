<?php
namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintPageController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with(['user', 'institution'])->where('user_id', Auth::id())->latest()->paginate(10);
        $institutions = Institution::all();
        return view('citizen.complaints.index', compact('complaints', 'institutions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'institution_id' => 'nullable|exists:institutions,id',
        ]);

        Complaint::create([
            'user_id' => Auth::id(),
            'institution_id' => $request->institution_id,
            'subject' => $request->subject,
            'category' => $request->category,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->route('complaints.index')->with('success', 'Complaint submitted successfully.');
    }

    public function show($id)
    {
        $complaint = Complaint::with(['user', 'institution'])->findOrFail($id);
        return view('citizen.complaints.show', compact('complaint'));
    }

    public function edit($id)
    {
        $complaint = Complaint::findOrFail($id);
        $institutions = Institution::all();
        return view('citizen.complaints.edit', compact('complaint', 'institutions'));
    }

    public function update(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'institution_id' => 'nullable|exists:institutions,id',
        ]);

        $complaint->update([
            'subject' => $request->subject,
            'category' => $request->category,
            'description' => $request->description,
            'institution_id' => $request->institution_id,
        ]);

        return redirect()->route('complaints.index')->with('success', 'Complaint updated successfully.');
    }

    public function destroy($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();

        return redirect()->route('complaints.index')->with('success', 'Complaint deleted successfully.');
    }
}
