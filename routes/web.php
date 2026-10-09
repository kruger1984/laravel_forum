<?php

use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', []);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('sections', SectionController::class);
    Route::resource('branches', BranchController::class);
    Route::resource('themes', ThemeController::class);
    Route::resource('messages', MessageController::class);

    Route::get('sections/{section}/branches', [SectionController::class, 'branchIndex']);
    Route::get('sections/{section}/branches_except/{branch}', [SectionController::class, 'branchIndexExcept']);

    Route::get('branches/{branch}/themes/create', [BranchController::class, 'themeCreate'])
         ->name('branches.themes.create');

    Route::post('messages/{message}/likes', [MessageController::class, 'toggleLike'])
         ->name('messages.likes.toggle');

    Route::post('messages/{message}/complaints', [MessageController::class, 'storeComplaint'])
         ->name('messages.complaints.store');


    Route::get('/users/personal', [UserController::class, 'personal'])->name('users.personal');
    Route::patch('/users/personal', [UserController::class, 'update']);

    Route::post('images', [ImageController::class, 'store']);

    Route::get('admin', [MainController::class, 'index'])->name('admin.main.index');
    Route::get('admin/complaints', [ComplaintController::class, 'index'])->name('admin.complaints.index');
    Route::patch('admin/complaints/{complaint}', [ComplaintController::class, 'update'])->name('admin.complaints.update');

    Route::get('admin/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::get('admin/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
    Route::post('admin/roles', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::get('admin/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
