<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Show the menu list along with permissions
    public function index()
    {
        $permissions = Permission::all();
        $menus = Menu::all(); // Assuming your menus table has fields: id, name, slug
        return view('admin.menu.index', compact('permissions', 'menus'));
    }

    // Show a single menu detail (optional)
    public function show(Menu $menu)
    {
        return view('admin.menu.show', compact('menu'));
    }

    // Show the edit form for a menu
    public function edit(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }

    // Handle the update form submission
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'menu_name' => 'required|string|max:255',
            'link' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'user_role' => 'required|in:admin,institution,citizen',
        ]);

        $menu->update([
            'menu_name' => $request->menu_name,
            'link' => $request->link,
            'icon' => $request->icon,
            'user_role' => $request->user_role,
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu updated successfully.');
    }

    // Save new menu
    public function store(Request $request)
    {
        $request->validate([
            'menu_name' => 'required|string|max:255',
            'link' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'user_role' => 'required|string|max:255',
        ]);

        Menu::create([
            'menu_name' => $request->menu_name,
            'link' => $request->link,
            'icon' => $request->icon,
            'user_role' => $request->user_role,
            'visible_in_sidebar' => false,
        ]);

        return redirect()->back()->with('success', 'Menu created successfully.');
    }

    // Toggle visibility on sidebar
    public function toggle(Menu $menu)
    {
        $menu->visible_in_sidebar = !$menu->visible_in_sidebar;
        $menu->save();

        return back()->with('success', 'Menu visibility updated.');
    }

    // Permanently delete menu
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->back()->with('success', 'Menu deleted successfully.');
    }
}
