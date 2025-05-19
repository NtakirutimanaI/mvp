<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\Category;
use App\Models\Institution;

class InstitutionController extends Controller
{
    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        $institutionId = auth()->user()->institution->id;

        $totalSubmissions = Complaint::where('institution_id', $institutionId)->count();
        $pendingCount = Complaint::where('institution_id', $institutionId)->where('status', 'Pending')->count();
        $resolvedCount = Complaint::where('institution_id', $institutionId)->where('status', 'Resolved')->count();
        $inProgressCount = Complaint::where('institution_id', $institutionId)->where('status', 'In Progress')->count();

        $submissions = Complaint::where('institution_id', $institutionId)
            ->latest()
            ->paginate(10);

        return view('institution.dashboard', compact(
            'totalSubmissions', 
            'pendingCount', 
            'resolvedCount', 
            'inProgressCount', 
            'submissions'
        ));
    }

    // other methods...
}
