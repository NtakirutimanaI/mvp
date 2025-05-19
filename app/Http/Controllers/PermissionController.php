<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Menu;

class PermissionController extends Controller
{
    // Display all permissions and menus
    public function index()
    {
        $permissions = Permission::all();
        $menus = Menu::all(); // assuming Menu is your model for the menus table

        return view('admin.permissions.index', compact('permissions', 'menus'));
    }

    // Store or update permission settings
    public function store(Request $request)
    {
        $data = $request->validate([
            'role' => 'required|string',
        ]);

        // Set permission flags
        foreach (['read', 'create', 'edit', 'delete', 'approve'] as $perm) {
            $data[$perm] = $request->has($perm) ? 1 : 0;
        }

        Permission::updateOrCreate(['role' => $data['role']], $data);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission saved successfully.');
    }

    // Edit a specific permission
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('permission'));
    }
}
