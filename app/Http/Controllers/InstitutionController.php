<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\Institution;
use App\Models\User;

class InstitutionController extends Controller
{
    /**
     * List dashboard overview for admin or general viewing
     */
    public function index()
    {
        $totalUsers = User::count();
        $totalInstitutions = Institution::count();
        $totalCategories = Category::count();
        $totalComplaints = Complaint::count();

        return view('dashboards.institution', compact(
            'totalUsers',
            'totalInstitutions',
            'totalCategories',
            'totalComplaints'
        ));
    }

    /**
     * Show institution-specific dashboard for the logged-in user
     */
    public function dashboard()
    {
        $institution = auth()->user()->institution;

        if (!$institution) {
            abort(403, 'No institution associated with this user.');
        }

        $institutionId = $institution->id;

        $totalSubmissions = Complaint::where('institution_id', $institutionId)->count();
        $pendingCount = Complaint::where('institution_id', $institutionId)->where('status', 'pending')->count();
        $resolvedCount = Complaint::where('institution_id', $institutionId)->where('status', 'resolved')->count();
        $inProgressCount = Complaint::where('institution_id', $institutionId)->where('status', 'in_progress')->count();

        $totalUsers = User::where('institution_id', $institutionId)->count();

        $submissions = Complaint::where('institution_id', $institutionId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('institution.dashboard', compact(
            'totalSubmissions',
            'pendingCount',
            'resolvedCount',
            'inProgressCount',
            'totalUsers',
            'submissions'
        ));
    }

    /**
     * List all complaints belonging to the logged-in institution
     */
    public function complaints()
    {
        $institutionId = auth()->user()->institution_id;

        $complaints = Complaint::where('institution_id', $institutionId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('institution.complaints.index', compact('complaints'));
    }

    /**
     * Show a specific complaint detail
     */
    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);
        return view('institution.complaints.show', compact('complaint'));
    }

    /**
     * Respond to a complaint
     */
    public function respond(Request $request, $id)
    {
        $request->validate([
            'response' => 'required|string',
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        $complaint = Complaint::findOrFail($id);
        $complaint->update([
            'response' => $request->response,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Response submitted successfully.');
    }
    public function getInstitutions()
    {
        return Institution::all();
    }
}
