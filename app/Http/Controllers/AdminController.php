<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\Institution;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Admin dashboard overview
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalInstitutions = Institution::count();
        $totalCategories = Category::count();
        $totalComplaints = Complaint::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalInstitutions',
            'totalCategories',
            'totalComplaints'
        ));
    }

    /**
     * View complaints data across all institutions (admin view)
     */
    public function viewAllComplaints()
    {
        $submissions = Complaint::with(['user', 'institution', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.complaints.index', compact('submissions'));
    }
    public function permissions()
{
    // Example data - you can fetch from DB or config as needed
    $permissions = [
        'view_users',
        'manage_complaints',
        'edit_categories',
        'access_reports',
    ];

    return view('admin.permissions.index', compact('permissions'));
}

}
