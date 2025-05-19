<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ComplaintPageController;




require __DIR__.'/auth.php';

// routes/web.php
Route::get('/', function () {
    return view('auth.register');
});

Route::get('/', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');



Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Protected dashboards for each role



// routes/web.php

Route::get('/register', [RegisteredUserController::class, 'create'])
        ->middleware('guest')
        ->name('register');


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:agency'])->group(function () {
    Route::get('/agency/dashboard', [AgencyController::class, 'index'])->name('agency.dashboard');
});

Route::middleware(['auth', 'role:citizen'])->group(function () {
    Route::get('/citizen/dashboard', [CitizenController::class, 'index'])->name('citizen.dashboard');
});

use App\Http\Controllers\UserController;

Route::get('/register', [UserController::class, 'create'])->name('register');
Route::post('/register', [UserController::class, 'store']);

// Optional CRUD routes:
Route::resource('users', UserController::class)->except(['create', 'store']);




Route::middleware('auth')->group(function () {
    Route::view('/dashboard/admin', 'dashboards.admin')->name('dashboard.admin');
Route::get('/dashboard/institution', [App\Http\Controllers\InstitutionController::class, 'index'])->name('dashboard.institution');
    Route::view('/dashboard/citizen', 'dashboards.citizen')->name('dashboard.citizen');
});

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');


Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// web.php

Route::get('/', [SubmissionController::class, 'create']);
Route::post('/submit', [SubmissionController::class, 'store']);

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index']);
    Route::post('/submission/{submission}/status', [AdminController::class, 'updateStatus']);
    Route::post('/submission/{submission}/response', [ResponseController::class, 'store']);
});


 // Agency routes
    Route::prefix('agency')->middleware('role:agency')->group(function () {
        Route::get('dashboard', [AgencyController::class, 'dashboard'])->name('agency.dashboard');
        // routes for responding, updating status etc
    });

    // Citizen routes
    Route::prefix('citizen')->middleware('role:citizen')->group(function () {
        Route::get('dashboard', [CitizenController::class, 'dashboard'])->name('citizen.dashboard');
        Route::get('submission/create', [CitizenController::class, 'create'])->name('citizen.submission.create');
        Route::post('submission/store', [CitizenController::class, 'store'])->name('citizen.submission.store');
        // view submissions, responses, comments
    });

    //Inbox
    Route::get('/inbox', function () {
    return view('inbox'); // Create a view named inbox.blade.php later
})->name('inbox');

//Notifications
Route::get('/notifications', function () {
    return view('notifications'); // Create this view later
})->name('notifications');
//Profile
Route::get('/profile', function () {
    return view('profile'); // create this view file later
})->name('profile');
//Settings
Route::get('/settings', function () {
    return view('settings');  // create this view file later
})->name('settings');


//Menu
Route::prefix('admin')->middleware('auth', 'can:admin')->group(function() {
    Route::get('/menu', [MenuController::class, 'index'])->name('admin.menu.index');
    Route::post('/menu', [MenuController::class, 'store'])->name('admin.menu.store');
    Route::patch('/menu/{menu}/toggle', [MenuController::class, 'toggleVisibility'])->name('admin.menu.toggle');
    Route::delete('/menu/{menu}', [MenuController::class, 'destroy'])->name('admin.menu.destroy');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('menu', MenuController::class);
});
//Toggle
Route::patch('/admin/menu/{menu}/toggle', [MenuController::class, 'toggle'])->name('admin.menu.toggle');
Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
Route::get('menu/{menu}/edit', [MenuController::class, 'edit'])->name('menu.edit');
Route::put('/admin/menu/{id}', [MenuController::class, 'update'])->name('admin.menu.update');
Route::resource('menus', Admin\MenuController::class);

//Users
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::resource('users', UserController::class);

//Permissions
Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('permissions.index');
Route::post('/admin/permissions', [PermissionController::class, 'store'])->name('permissions.store');
Route::resource('permissions', PermissionController::class);
Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
Route::post('/get-menus-by-role', [PermissionController::class, 'getMenusByRole'])->name('permissions.getMenus');
Route::get('/admin/permissions', [AdminController::class, 'permissions'])->name('admin.permissions');
Route::get('/admin/permissions', [AdminController::class, 'permissions']);


//
Route::get('/permissions', [PermissionController::class, 'permissions'])->name('permissions.index');
Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
Route::get('/permissions/menus', [PermissionController::class, 'getMenusByRole'])->name('permissions.menus');

Route::get('/dashboard/institution', [InstitutionController::class, 'index'])->name('dashboard.institution');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/institution', [InstitutionController::class, 'index'])->name('dashboard.institution');
});
Route::get('/institution', [InstitutionController::class, 'index']);
Route::get('/institution', [InstitutionController::class, 'dashboard']);
Route::get('/institution', [InstitutionController::class, 'dashboard'])->name('institution.dashboard');
//Updates

Route::prefix('admin/permissions')->middleware('auth')->group(function () {
    Route::get('/', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('/save', [PermissionController::class, 'store'])->name('permissions.store');
});


//
Route::middleware('auth')->prefix('institution')->name('institution.')->group(function () {
    Route::get('/', [InstitutionController::class, 'index'])->name('dashboard');
    // add more institution-related routes here as needed
});

// routes/web.php



Route::middleware(['auth'])->group(function () {
    
    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Institution Dashboard
    Route::get('/institution/dashboard', [InstitutionController::class, 'dashboard'])->name('institution.dashboard');

    // Citizen Dashboard
    Route::get('/citizen/dashboard', [CitizenController::class, 'dashboard'])->name('citizen.dashboard');
    Route::get('/citizen/complaints/create', [CitizenController::class, 'createComplaint'])->name('citizen.complaints.create');
    Route::post('/citizen/complaints', [CitizenController::class, 'storeComplaint'])->name('citizen.complaints.store');
    Route::get('/citizen/complaints/{id}', [CitizenController::class, 'showComplaint'])->name('citizen.complaints.show');
});

Route::get('/admin/permissions', [AdminController::class, 'permissions']);


Route::middleware(['auth'])->group(function () {
    Route::get('/institution/dashboard', [InstitutionController::class, 'dashboard'])->name('institution.dashboard');
});

//Submissions Routes
Route::get('/institutions/submissions/index', [SubmissionController::class, 'index'])->name('submissions.index');

Route::resource('submissions', App\Http\Controllers\SubmissionController::class);

Route::resource('submissions', SubmissionController::class);
Route::post('/submissions', [SubmissionController::class, 'store'])->name('submissions.store');

// Citizen routes
Route::prefix('citizen')->middleware(['auth'])->group(function () {
    Route::get('/complaints', [ComplaintPageController::class, 'index'])->name('complaints.index');
    Route::post('/complaints', [ComplaintPageController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/{id}', [ComplaintPageController::class, 'show'])->name('complaints.show');
    Route::get('/complaints/{id}/edit', [ComplaintPageController::class, 'edit'])->name('complaints.edit');
    Route::put('/complaints/{id}', [ComplaintPageController::class, 'update'])->name('complaints.update');
    Route::delete('/complaints/{id}', [ComplaintPageController::class, 'destroy'])->name('complaints.destroy');
});
