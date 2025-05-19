<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Submission;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Show all users (optional)
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Show registration form (create)
    public function create()
    {
        return view('users.register');  // the blade with your form
    }

    // Save new user from form
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'required|email|unique:users,email',
            'role'      => 'required|in:citizen,Institution,admin',
            'password'  => 'required|confirmed|min:6',
        ]);

        User::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'phone'     => $request->phone,
            'email'     => $request->email,
            'role'      => $request->role,
            'password'  => $request->password,  // will be hashed by mutator
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    // Show a single user detail (optional)
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Show form to edit user (optional)
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Update user info (optional)
    public function update(Request $request, User $user)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'required|email|unique:users,email,'.$user->id,
            'role'      => 'required|in:citizen,Institution,admin',
            'password'  => 'nullable|confirmed|min:6',
        ]);

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->password) {
            $user->password = $request->password; // hashed by mutator
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Delete user (optional)
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    // User dashboard showing submissions for their institution
    public function dashboard()
    {
        $institution = auth()->user()->institution;

        if (!$institution) {
            abort(403, 'No institution associated with this user.');
        }

        $institutionId = $institution->id;

        $submissions = Submission::where('institution_id', $institutionId)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('agency.dashboard', compact('submissions'));
    }
}
